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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: PS5, PS4
            $table->string('type'); // Contoh: Standard Edition, Slim
            $table->string('code')->unique(); // Kode unit unik, biar gampang identifikasi
            $table->string('condition'); // Kondisi: Baik, Rusak, dll.
            $table->decimal('price_per_day', 10, 2); // Harga per hari, pakai decimal biar presisi
            $table->integer('stock_available')->default(0); // Stok yang tersedia
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};