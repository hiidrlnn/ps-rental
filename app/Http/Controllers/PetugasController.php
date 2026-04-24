<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Unit;
use App\Models\Rental;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceNotification;

class PetugasController extends Controller
{
    public function dashboard()
    {
        return view('petugas.dashboard');
    }

public function showRentForm()
{
    return view('petugas.rental.customer-form');
}

public function storeCustomer(Request $request)
{
    // =========================
    // VALIDASI KETAT
    // =========================
    $validated = $request->validate([
        'name' => [
            'required',
            'string',
            'min:3',
            'max:100',
            'regex:/^[a-zA-Z\s]+$/'
        ],

        'address' => [
            'required',
            'string',
            'min:10',
            'max:255',
            'regex:/^[a-zA-Z0-9\s,.-]+$/'
        ],

        'phone' => [
            'required',
            'regex:/^08[0-9]{8,11}$/'
        ],

        'email' => [
            'required',
            'email',
            'max:100'
        ],

        'rental_datetime' => [
            'required',
            'date'
        ],

    ], [
        // =========================
        // CUSTOM ERROR MESSAGE
        // =========================
        'name.required' => 'Nama wajib diisi',
        'name.regex' => 'Nama hanya boleh huruf',
        'name.min' => 'Nama minimal 3 karakter',

        'address.required' => 'Alamat wajib diisi',
        'address.min' => 'Alamat terlalu pendek',

        'phone.required' => 'Nomor wajib diisi',
        'phone.regex' => 'Nomor harus format Indonesia (08xxxx)',

        'email.required' => 'Email wajib diisi',
        'email.email' => 'Format email tidak valid',

        'rental_datetime.required' => 'Tanggal wajib diisi',
    ]);

    // =========================
    // NORMALISASI DATA
    // =========================
    $validated['name'] = ucwords(strtolower(trim($validated['name'])));
    $validated['address'] = trim($validated['address']);
    $validated['phone'] = preg_replace('/[^0-9]/', '', $validated['phone']);
    $validated['email'] = strtolower(trim($validated['email']));

    // =========================
    // SIMPAN / AMBIL CUSTOMER
    // =========================
    $customer = Customer::updateOrCreate(
        ['email' => $validated['email']], // cek by email
        [
            'name' => $validated['name'],
            'address' => $validated['address'],
            'phone' => $validated['phone'],
        ]
    );

    // =========================
    // SIMPAN KE SESSION
    // =========================
    session([
        'current_customer_id' => $customer->id,
        'rental_datetime' => $validated['rental_datetime']
    ]);

    // =========================
    // REDIRECT
    // =========================
    return redirect()->route('petugas.rental.select-unit');
}

    public function selectUnit()
    {
        $customer_id = session('current_customer_id');

        if (!$customer_id) {
            return redirect()->route('petugas.rental.form')
                ->withErrors('Isi data pelanggan dulu');
        }

        $customer = Customer::findOrFail($customer_id);
        $units = Unit::where('stock_available', '>', 0)->get();

        return view('petugas.rental.select-unit', compact('customer', 'units'));
    }

public function confirmRental(Request $request)
{
    // =========================
    // VALIDASI INPUT
    // =========================
    $validated = $request->validate([
        'unit_id' => 'required|exists:units,id',
        'quantity' => 'required|integer|min:1',
        'duration_days' => 'required|integer|min:1',
    ]);

    // =========================
    // AMBIL DATA SESSION
    // =========================
    $customer_id = session('current_customer_id');
    $rentalDatetime = session('rental_datetime');

    if (!$customer_id || !$rentalDatetime) {
        return redirect()->route('petugas.rental.form')
            ->with('error', 'Data pelanggan atau waktu sewa tidak ditemukan');
    }

    // =========================
    // AMBIL DATA DB
    // =========================
    $customer = Customer::findOrFail($customer_id);
    $unit = Unit::findOrFail($validated['unit_id']);

    // =========================
    // HITUNG HARGA
    // =========================
    $subtotal_price = $unit->price_per_day * $validated['quantity'];
    $total_price = $subtotal_price * $validated['duration_days'];

    // =========================
    // HITUNG TANGGAL
    // =========================
    $rentalDate = \Carbon\Carbon::parse($rentalDatetime);
    $returnDate = $rentalDate->copy()->addDays($validated['duration_days']);

    // =========================
    // SIMPAN KE SESSION
    // =========================
    session()->put('rental_details', [
        'customer_id' => $customer->id,
        'unit_id' => $unit->id,
        'quantity' => $validated['quantity'],
        'duration_days' => $validated['duration_days'],
        'subtotal_price' => $subtotal_price,
        'total_price' => $total_price,
        'rental_datetime' => $rentalDatetime,
        'return_datetime' => $returnDate->toDateTimeString(),
    ]);

    // =========================
    // AMBIL LAGI (BIAR CONSISTENT)
    // =========================
    $rentalDetails = session('rental_details');

    // =========================
    // RETURN VIEW
    // =========================
    return view('petugas.rental.confirm', compact(
        'customer',
        'unit',
        'validated',
        'rentalDetails',
        'rentalDate',
        'returnDate'
    ));
}

public function showPaymentForm()
{
    $rentalDetails = session('rental_details');

    if (!$rentalDetails) {
        return redirect()->route('petugas.rental.form');
    }

    $customer = Customer::findOrFail($rentalDetails['customer_id']);
    $unit = Unit::findOrFail($rentalDetails['unit_id']);

    return view('petugas.rental.payment', compact('rentalDetails', 'customer', 'unit'));
}

    // =========================
    // 🔥 PROCESS PAYMENT
    // =========================
    public function processPayment(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:manual,xendit',
            'amount_paid' => 'nullable|numeric|min:0'
        ]);

        $data = session('rental_details');

        if (!$data) {
            return back()->withErrors('Session hilang');
        }

        DB::beginTransaction();

        try {
            $unit = Unit::findOrFail($data['unit_id']);

            // 🔥 CREATE RENTAL
            $rental = Rental::create([
                'customer_id' => $data['customer_id'],
                'unit_id' => $unit->id,
                'quantity' => $data['quantity'],
                'duration_days' => $data['duration_days'],
                'rental_date' => now(),
                'return_date' => now()->addDays($data['duration_days']),
                'subtotal_price' => $data['total_price'],
                'total_price' => $data['total_price'],
                'status' => 'waiting_payment',
            ]);

            $unit->decrement('stock_available', $data['quantity']);

            // =========================
            // 🔥 MANUAL
            // =========================
            if ($request->payment_method === 'manual') {

                $amount = (int) $request->amount_paid;

                if ($amount < $data['total_price']) {
                    throw new \Exception('Uang kurang');
                }

                Payment::create([
                    'external_id' => 'rental_'.$rental->id.'_'.time(),
                    'rental_id' => $rental->id,
                    'payment_type' => 'rental',
                    'method' => 'manual',
                    'amount_paid' => $amount,
                    'change_amount' => $amount - $data['total_price'],
                    'status' => 'completed',
                ]);

                $rental->update(['status' => 'rented']);

                DB::commit();

                return redirect()->route('petugas.rental.success', $rental->id);
            }

            // =========================
            // 🔥 XENDIT
            // =========================
            DB::commit();

            return redirect('/pay?amount='.$data['total_price']
                .'&rental_id='.$rental->id
                .'&type=rental');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage());
        }
    }

    // =========================
    // 🔥 RETURN
    // =========================
   public function processReturn($id)
{
    try {

        DB::beginTransaction();

        // 🔥 LOCK DATA (ANTI DOUBLE PROCESS)
        $rental = Rental::with('unit')
            ->lockForUpdate()
            ->findOrFail($id);

        // 🔒 CEGAH DOUBLE RETURN
        if ($rental->status === 'returned') {
            DB::rollBack();
            return back()->with('error', 'Sudah dikembalikan');
        }

        $now = Carbon::now();
        $expected = Carbon::parse($rental->return_date);

        $fine = 0;

        // =========================
        // 🔥 HITUNG DENDA
        // =========================
        if ($now->greaterThan($expected)) {

            $minutes = $now->diffInMinutes($expected);

            $fine = ceil($minutes / 60) * ($rental->unit->price_per_day / 24);

            // 🔥 BULATKAN BIAR RAPI
            $fine = round($fine);
        }

        // =========================
        // 🔥 UPDATE RENTAL
        // =========================
        if (!$rental->actual_return_time) {

            $rental->update([
                'actual_return_time' => $now,
                'fine_amount' => $fine,
                'status' => $fine > 0 ? 'late' : 'returned'
            ]);

            // 🔥 TAMBAH STOCK SEKALI SAJA
            $rental->unit->increment('stock_available', $rental->quantity);
        }

        DB::commit();

        // =========================
        // 🔥 JIKA ADA DENDA
        // =========================
        if ($fine > 0) {
            return redirect()->route('petugas.return.process-fine', [
                'rental_id' => $rental->id,
                'fine_amount' => $fine
            ]);
        }

        // =========================
        // 🔥 TANPA DENDA
        // =========================
        return redirect()->route('petugas.return.list')
            ->with('success', 'Pengembalian berhasil');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()->withErrors([
            'error' => $e->getMessage()
        ]);
    }
}

public function storeFinePayment(Request $request, $id)
{
    // =========================
    // 🔍 VALIDASI INPUT
    // =========================
    $request->validate([
        'fine_amount' => 'required|numeric|min:0',
        'payment_method' => 'required|in:manual,xendit',
        'amount_paid' => 'nullable|numeric|min:0'
    ]);

    $rental = Rental::with('customer')->findOrFail($id);

    // =========================
    // 🔒 CEGAH DOUBLE PROSES
    // =========================
    if ($rental->status === 'returned') {
        return redirect()->route('petugas.return.list')
            ->with('error', 'Sudah selesai');
    }

    DB::beginTransaction();

    try {

        $fine = (int) $request->fine_amount;

        // =========================
        // 🔴 MANUAL PAYMENT
        // =========================
        if ($request->payment_method === 'manual') {

            $amount = (int) $request->amount_paid;

            if ($amount < $fine) {
                throw new \Exception('Uang kurang');
            }

            // 🔥 SIMPAN PAYMENT (PASTIKAN FIELD BENAR)
            $payment = Payment::create([
                'external_id' => 'fine_'.$rental->id.'_'.time(), // unik
                'rental_id' => $rental->id,
                'payment_type' => 'fine', // 🔥 WAJIB (JANGAN PAKAI type)
                'method' => 'manual',
                'amount_paid' => $amount,
                'change_amount' => $amount - $fine,
                'status' => 'completed',
            ]);

            // 🔥 UPDATE RENTAL
            $rental->update([
                'status' => 'returned'
            ]);

            DB::commit();

            // 🔥 REDIRECT KE SUCCESS
            return redirect()->route('petugas.rental.success', $rental->id);
        }

        // =========================
        // 🔵 XENDIT PAYMENT
        // =========================
        if ($request->payment_method === 'xendit') {

            DB::commit();

            return redirect('/pay?amount=' . $fine
                . '&rental_id=' . $rental->id
                . '&type=fine');
        }

    } catch (\Exception $e) {

        DB::rollBack();

        return back()->withErrors([
            'error' => $e->getMessage()
        ]);
    }
}

    public function showReturnList()
{
    $rentals = Rental::with('customer', 'unit')
        ->whereIn('status', ['rented', 'late'])
        ->get();

    return view('petugas.return.list', compact('rentals'));
}

public function showFinePaymentForm($rental_id, $fine_amount)
{
    $rental = Rental::with('customer', 'unit')->findOrFail($rental_id);

    return view('petugas.return.fine-payment', [
        'rental' => $rental,
        'fine_amount' => $fine_amount
    ]);
}

}