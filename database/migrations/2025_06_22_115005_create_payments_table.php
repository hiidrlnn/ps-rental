<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained()->onDelete('cascade'); // ID sewa yang dibayar
            $table->enum('type', ['rental', 'fine']); // Jenis pembayaran: untuk sewa atau denda
            $table->string('method'); // Metode pembayaran: Manual, Midtrans
            $table->decimal('amount_paid', 10, 2); // Nominal yang dibayarkan
            $table->decimal('change_amount', 10, 2)->default(0); // Kembalian (untuk pembayaran manual)
            $table->string('midtrans_transaction_id')->nullable(); // ID transaksi Midtrans (jika pakai Midtrans)
            $table->enum('status', ['pending', 'completed', 'failed'])->default('completed'); // Status pembayaran
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};