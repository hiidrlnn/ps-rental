<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MidtransNotificationController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// --- Rute untuk Otentikasi (Login/Logout) ---
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- Rute untuk Petugas ---
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [PetugasController::class, 'dashboard'])->name('dashboard');

    // Menu Penyewaan
    Route::get('/rental/form', [PetugasController::class, 'showRentForm'])->name('rental.form');
    Route::post('/rental/customer', [PetugasController::class, 'storeCustomer'])->name('rental.store-customer');
    Route::get('/rental/select-unit', [PetugasController::class, 'selectUnit'])->name('rental.select-unit');
    Route::post('/rental/confirm', [PetugasController::class, 'confirmRental'])->name('rental.confirm');
    Route::get('/rental/payment', [PetugasController::class, 'showPaymentForm'])->name('rental.payment');
    Route::post('/rental/process-payment', [PetugasController::class, 'processPayment'])->name('rental.process-payment');

    // Menu Pengembalian
    Route::get('/returns', [PetugasController::class, 'showReturnList'])->name('return.list');
    Route::post('/returns/{rental_id}/process', [PetugasController::class, 'processReturn'])->name('return.process');
    Route::get('/returns/{rental_id}/fine/payment/{fine_amount}', [PetugasController::class, 'showFinePaymentForm'])->name('return.process-fine');
    Route::post('/returns/{rental_id}/fine-payment', [PetugasController::class, 'storeFinePayment'])->name('return.store-fine-payment');
});

// --- Rute untuk Pemilik ---
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('dashboard');

    // Menu Stock Unit PS (CRUD)
    Route::get('/units', [OwnerController::class, 'unitIndex'])->name('units.index');
    Route::get('/units/create', [OwnerController::class, 'unitCreate'])->name('units.create');
    Route::post('/units', [OwnerController::class, 'unitStore'])->name('units.store');
    Route::get('/units/{unit}/edit', [OwnerController::class, 'unitEdit'])->name('units.edit');
    Route::put('/units/{unit}', [OwnerController::class, 'unitUpdate'])->name('units.update');
    Route::delete('/units/{unit}', [OwnerController::class, 'unitDestroy'])->name('units.destroy');
    Route::post('/units/sync-stock', [OwnerController::class, 'syncStock'])->name('units.sync-stock');

    // Menu Laporan Penyewaan
    Route::get('/reports', [OwnerController::class, 'reportIndex'])->name('reports.index');
});


// --- Rute untuk Profile (Baik Owner maupun Petugas) ---
    Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'show'])->name('show');

});
    //--- Rute Payment Gateway Xendit ---
        Route::get('/pay', [PaymentController::class, 'pay']);
        Route::get('/success', [PaymentController::class, 'success']);
        Route::get('/failed', [PaymentController::class, 'failed']);
        Route::post('/xendit/webhook', [PaymentController::class, 'callback']);
        Route::get('/payment-success', [PaymentController::class, 'success']);
        Route::get('/petugas/rental/success/{id}', [PaymentController::class, 'success'])->name('petugas.rental.success');
        Route::post('/petugas/rental/send-invoice-email/{id}', [PaymentController::class, 'sendInvoiceEmail']);

        