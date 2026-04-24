<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Rental;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    // Dashboard Statistik Pemilik
    public function dashboard()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $totalRentalsMonth = Rental::whereMonth('rental_date', $currentMonth)
                                    ->whereYear('rental_date', $currentYear)
                                    ->count();

        $availableUnits = Unit::sum('stock_available');

        // --- PERHITUNGAN PENDAPATAN BERSIH YANG AKURAT ---

        // 1. Pendapatan Bulanan (Monthly Revenue)
        $monthlyRevenueResult = Payment::leftJoin('rentals', 'payments.rental_id', '=', 'rentals.id')
            ->whereMonth('payments.created_at', $currentMonth)
            ->whereYear('payments.created_at', $currentYear)
            ->where('payments.status', 'completed')
            ->select(DB::raw('SUM(CASE
                WHEN payments.type = "fine" THEN payments.amount_paid       /* Untuk denda, ambil jumlah yang dibayar */
                WHEN payments.type = "rental" THEN rentals.total_price     /* Untuk sewa, ambil total harga rental (sudah bersih dari kembalian) */
                ELSE 0
            END) as actual_revenue'))
            ->first();
        $monthlyRevenue = $monthlyRevenueResult->actual_revenue ?? 0;


        // 2. Total Pendapatan (Total Revenue - Seluruhnya)
        $totalRevenueResult = Payment::leftJoin('rentals', 'payments.rental_id', '=', 'rentals.id')
            ->where('payments.status', 'completed')
            ->select(DB::raw('SUM(CASE
                WHEN payments.type = "fine" THEN payments.amount_paid
                WHEN payments.type = "rental" THEN rentals.total_price
                ELSE 0
            END) as actual_revenue'))
            ->first();
        $totalRevenue = $totalRevenueResult->actual_revenue ?? 0;


        // 3. Pendapatan Harian Bulan Ini (Untuk Diagram)
        $dailyRevenueData = Payment::leftJoin('rentals', 'payments.rental_id', '=', 'rentals.id')
            ->select(
                DB::raw('DATE(payments.created_at) as date'),
                DB::raw('SUM(CASE
                    WHEN payments.type = "fine" THEN payments.amount_paid
                    WHEN payments.type = "rental" THEN rentals.total_price
                    ELSE 0
                END) as daily_total_revenue') // Alias baru untuk total pendapatan harian
            )
            ->whereMonth('payments.created_at', $currentMonth)
            ->whereYear('payments.created_at', $currentYear)
            ->where('payments.status', 'completed')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Format data untuk Chart.js (label tanggal & data pendapatan)
        $dailyRevenueLabels = [];
        $dailyRevenueValues = [];
        foreach ($dailyRevenueData as $data) {
            $dailyRevenueLabels[] = Carbon::parse($data->date)->format('d M');
            $dailyRevenueValues[] = $data->daily_total_revenue; // Gunakan alias baru
        }
        // --- AKHIR PERHITUNGAN PENDAPATAN BERSIH ---


        // Data Unit Terlaris (tetap sama)
        $topSellingUnitsData = Rental::select(
                                DB::raw('units.name as unit_name'),
                                DB::raw('units.type as unit_type'),
                                DB::raw('SUM(rentals.quantity) as total_quantity_rented')
                            )
                            ->join('units', 'rentals.unit_id', '=', 'units.id')
                            ->groupBy('units.id', 'units.name', 'units.type')
                            ->orderBy('total_quantity_rented', 'desc')
                            ->limit(5)
                            ->get();

        $topSellingUnitsLabels = [];
        $topSellingUnitsValues = [];
        foreach ($topSellingUnitsData as $data) {
            $topSellingUnitsLabels[] = $data->unit_name . ' (' . $data->unit_type . ')';
            $topSellingUnitsValues[] = $data->total_quantity_rented;
        }

        return view('owner.dashboard', compact(
            'totalRentalsMonth',
            'availableUnits',
            'monthlyRevenue',
            'totalRevenue',
            'dailyRevenueLabels',
            'dailyRevenueValues',
            'topSellingUnitsLabels',
            'topSellingUnitsValues'
        ));
    }

    // Kelola Stok Unit (CRUD)
    public function unitIndex()
    {
        $units = Unit::all();
        return view('owner.units.index', compact('units'));
    }

    public function unitCreate()
    {
        return view('owner.units.create');
    }

    public function unitEdit(Unit $unit)
    {
        return view('owner.units.edit', compact('unit'));
    }

    public function unitDestroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('owner.units.index')->with('success', 'Unit PS berhasil dihapus!');
    }

    // Laporan Penyewaan
    public function reportIndex(Request $request) // Ini nama fungsi untuk laporan
    {
        $query = Rental::with('customer', 'unit'); // <--- PASTIKAN RELASI INI ADA

        // Filter berdasarkan tanggal (opsional)
        if ($request->has('start_date') && $request->has('end_date')) {
            $start_date = Carbon::parse($request->start_date)->startOfDay();
            $end_date = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('rental_date', [$start_date, $end_date]);
        }

        $reports = $query->orderBy('rental_date', 'desc')->get(); // Urutkan berdasarkan tanggal sewa terbaru

        return view('owner.reports.index', compact('reports'));
    }

public function unitStore(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'type' => 'required|string|max:255',
        'image_url' => 'nullable|string|max:255',
        'code' => 'required|string|unique:units,code|max:255',
        'condition' => 'required|string|max:255',
        'price_per_day' => 'required|numeric|min:0',
        'stock_available' => 'required|integer|min:0',
    ]);

    // 🔥 SAMAKAN TOTAL & AVAILABLE SAAT CREATE
    $validated['stock_total'] = $validated['stock_available'];

    Unit::create($validated);

    return redirect()->route('owner.units.index')
        ->with('success', 'Unit PS berhasil ditambahkan!');
}

public function unitUpdate(Request $request, Unit $unit)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'type' => 'required|string|max:255',
        'image_url' => 'nullable|string|max:255',
        'code' => 'required|string|unique:units,code,' . $unit->id . '|max:255',
        'condition' => 'required|string|max:255',
        'price_per_day' => 'required|numeric|min:0',
        'stock_available' => 'required|integer|min:0',
    ]);

    DB::transaction(function () use ($unit, $validated) {

        // 🔥 HITUNG SELISIH STOCK
        $diff = $validated['stock_available'] - $unit->stock_available;

        // 🔥 UPDATE UNIT
        $unit->update([
            ...$validated,

            // 🔥 TOTAL IKUT DIUPDATE
            'stock_total' => $unit->stock_total + $diff
        ]);
    });

    return redirect()->route('owner.units.index')
        ->with('success', 'Unit PS berhasil diperbarui!');
}

public function syncStock()
{
    DB::transaction(function () {

        // 🔥 SAMAKAN TOTAL = AVAILABLE
        Unit::query()->update([
            'stock_total' => DB::raw('stock_available')
        ]);

    });

    return redirect()->back()->with('success', 'Stok berhasil disinkronkan!');
}
}