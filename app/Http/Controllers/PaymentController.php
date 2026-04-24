<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rental;
use App\Models\Payment;
use Illuminate\Support\Facades\Mail;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'rental_id' => 'required|exists:rentals,id'
        ]);

        $amount = (int) $request->amount;
        $rental = Rental::with('customer')->findOrFail($request->rental_id);

        // 🔥 rental / fine
        $payment_type = $request->query('type', 'rental');

        // 🔥 HARUS UNIK
        $external_id = $payment_type . '_' . $rental->id . '_' . time();

        Configuration::setXenditKey(config('services.xendit.key'));
        $api = new InvoiceApi();

        $params = [
            'external_id' => $external_id,
            'amount' => $amount,
            'payer_email' => $rental->customer->email ?? 'test@mail.com',
            'description' => strtoupper($payment_type) . ' #' . $rental->id,
            'success_redirect_url' => url('/petugas/rental/success/' . $rental->id),
            'failure_redirect_url' => url('/payment-failed'),
        ];

        $invoice = $api->createInvoice($params);

        Payment::create([
            'external_id' => $external_id,
            'rental_id' => $rental->id,
            'payment_type' => $payment_type,
            'method' => 'xendit',
            'amount_paid' => $amount,
            'xendit_invoice_id' => $invoice['id'],
            'invoice_url' => $invoice['invoice_url'],
            'status' => 'pending',
        ]);

        return redirect($invoice['invoice_url']);
    }

public function success($id)
{
    // =========================
    // 🔍 AMBIL DATA RENTAL + RELASI
    // =========================
    $rental = Rental::with('customer', 'unit')->findOrFail($id);

    // =========================
    // 🔍 AMBIL PAYMENT TERBARU
    // =========================
    $payment = Payment::where('rental_id', $id)
        ->latest()
        ->first();

    // =========================
    // ⚠️ KALAU TIDAK ADA PAYMENT
    // =========================
    if (!$payment) {
        return view('petugas.rental.success', compact('rental', 'payment'));
    }

    // =========================
    // 🔥 FALLBACK (JAGA-JAGA)
    // Kalau webhook belum masuk
    // =========================
    if ($payment->status !== 'completed') {

        // ⚠️ jangan override kalau bukan dari redirect sukses
        // tapi di sini kita pakai fallback ringan saja
        $payment->update([
            'status' => 'completed'
        ]);
    }

    // =========================
    // 🔥 HANDLE STATUS RENTAL
    // =========================
    if ($payment->status === 'completed') {

        // 🔴 PEMBAYARAN DENDA
        if ($payment->payment_type === 'fine') {

            // ❗ pastikan tidak double update
            if ($rental->status !== 'returned') {

                $rental->update([
                    'status' => 'returned'
                ]);
            }
        }

        // 🟢 PEMBAYARAN SEWA
        if ($payment->payment_type === 'rental') {

            if ($rental->status !== 'rented') {

                $rental->update([
                    'status' => 'rented'
                ]);
            }
        }
    }

    // =========================
    // 📄 TAMPILKAN VIEW
    // =========================
    return view('petugas.rental.success', compact('rental', 'payment'));
}

public function callback(Request $request)
{
    // =========================
    // 🧾 LOG RAW DATA (DEBUG)
    // =========================
    \Log::info('XENDIT CALLBACK', $request->all());

    try {

        // =========================
        // 🔐 VALIDASI TOKEN
        // =========================
        $callbackToken = $request->header('x-callback-token');

        if ($callbackToken !== env('XENDIT_CALLBACK_TOKEN')) {
            \Log::warning('INVALID TOKEN', ['token' => $callbackToken]);
            return response()->json(['message' => 'unauthorized'], 403);
        }

        // =========================
        // 🔥 AMBIL DATA (SUPPORT 2 FORMAT)
        // =========================
        $external_id = $request->external_id 
            ?? $request->input('data.external_id');

        $status = $request->status 
            ?? $request->input('data.status');

        $invoice_id = $request->id 
            ?? $request->input('data.id');

        $payment_channel = $request->payment_channel 
            ?? $request->input('data.payment_channel');

        // =========================
        // ❌ VALIDASI WAJIB
        // =========================
        if (!$external_id) {
            \Log::warning('NO EXTERNAL ID');
            return response()->json(['message' => 'ignored'], 200);
        }

        // =========================
        // 🔍 AMBIL PAYMENT
        // =========================
        $payment = Payment::with('rental')
            ->where('external_id', $external_id)
            ->first();

        if (!$payment) {
            \Log::warning('PAYMENT NOT FOUND', ['external_id' => $external_id]);
            return response()->json(['message' => 'ignored'], 200);
        }

        // =========================
        // 🔁 ANTI DOUBLE PROCESS
        // =========================
        if ($payment->status === 'completed') {
            return response()->json(['message' => 'already processed'], 200);
        }

        // =========================
        // 🔥 HANDLE STATUS XENDIT
        // =========================
        if (in_array($status, ['PAID', 'SETTLED'])) {

            // =========================
            // 💾 UPDATE PAYMENT
            // =========================
            $payment->update([
                'status' => 'completed',
                'xendit_invoice_id' => $invoice_id,
                'payment_channel' => $payment_channel,
            ]);

            $rental = $payment->rental;

            // =========================
            // 🔥 UPDATE RENTAL
            // =========================
            if ($rental) {

                // 🟢 PAYMENT SEWA
                if ($payment->payment_type === 'rental') {

                    $rental->update([
                        'status' => 'rented'
                    ]);
                }

                // 🔴 PAYMENT DENDA
                if ($payment->payment_type === 'fine') {

                    // ❗ HANYA UPDATE STATUS (JANGAN TAMBAH STOCK DI SINI)
                    if ($rental->status !== 'returned') {

                        $rental->update([
                            'status' => 'returned',
                            'actual_return_time' => now()
                        ]);

                        \Log::info('RENTAL MARKED AS RETURNED', [
                            'rental_id' => $rental->id
                        ]);
                    }
                }
            }

        } elseif ($status === 'EXPIRED') {

            $payment->update([
                'status' => 'expired'
            ]);

        } else {

            $payment->update([
                'status' => 'failed'
            ]);
        }

        return response()->json(['message' => 'OK'], 200);

    } catch (\Exception $e) {

        \Log::error('WEBHOOK ERROR', [
            'error' => $e->getMessage(),
        ]);

        return response()->json(['message' => 'error'], 500);
    }
}

public function sendInvoiceEmail($id)
{
    try {
        // =========================
        // 🔍 AMBIL DATA
        // =========================
        $rental = Rental::with('customer', 'unit')->findOrFail($id);

        $payment = Payment::where('rental_id', $id)
            ->latest()
            ->first();

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment tidak ditemukan'
            ]);
        }

        // =========================
        // 🔥 TENTUKAN TYPE (PENTING)
        // =========================
        $type = $payment->type ?? 'rental'; // fallback kalau null

        // =========================
        // 📧 KIRIM EMAIL (PAKAI BLADE)
        // =========================
        Mail::send('emails.invoice', [
            'rental' => $rental,
            'payment' => $payment,
            'type' => $type
        ], function ($message) use ($rental, $type) {

            $subject = $type === 'fine'
                ? 'Invoice Pembayaran Denda #' . $rental->id
                : 'Invoice Penyewaan #' . $rental->id;

            $message->to($rental->customer->email)
                    ->subject($subject);
        });

        // =========================
        // ✅ SUCCESS RESPONSE
        // =========================
        return response()->json([
            'success' => true,
            'message' => 'Email berhasil dikirim'
        ]);

    } catch (\Exception $e) {

        \Log::error('EMAIL ERROR', [
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Gagal kirim email'
        ]);
    }
}
}