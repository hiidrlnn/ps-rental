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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade'); // ID pelanggan
            $table->foreignId('unit_id')->constrained()->onDelete('cascade');     // ID unit PS
            $table->integer('quantity'); // Jumlah unit yang disewa (misal: 2 unit PS4 Slim)
            $table->integer('duration_days'); // Durasi sewa dalam hari
            $table->date('rental_date'); // Tanggal sewa
            $table->date('return_date'); // Tanggal harus kembali (estimasi)
            $table->dateTime('actual_return_time')->nullable(); // Waktu pengembalian aktual, bisa kosong kalau belum kembali
            $table->decimal('subtotal_price', 10, 2); // Harga sebelum denda
            $table->decimal('total_price', 10, 2); // Total harga (termasuk denda jika ada)
            $table->decimal('fine_amount', 10, 2)->default(0); // Jumlah denda
            $table->enum('status', ['rented', 'returned', 'late'])->default('rented'); // Status sewa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};