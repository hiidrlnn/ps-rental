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
        Schema::table('rentals', function (Blueprint $table) {
            // Mengubah tipe kolom rental_date dari DATE menjadi DATETIME
            // Metode ->change() digunakan untuk mengubah tipe kolom yang sudah ada.
            $table->dateTime('rental_date')->change(); 

            // Mengubah tipe kolom return_date dari DATE menjadi DATETIME
            $table->dateTime('return_date')->change(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            // Jika migrasi di-rollback, kembalikan tipe kolom ke DATE
            // Ini penting jika Anda perlu mengembalikan struktur tabel ke kondisi sebelumnya.
            $table->date('rental_date')->change();
            $table->date('return_date')->change();
        });
    }
};