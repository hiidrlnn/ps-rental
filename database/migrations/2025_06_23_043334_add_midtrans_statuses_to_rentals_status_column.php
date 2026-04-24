<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Perlu di-import untuk DB::statement

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah tipe kolom 'status' untuk menambahkan nilai enum baru
        // MySQL/PostgreSQL supports ALTER TABLE ... MODIFY COLUMN ... ENUM(...)
        // Ini akan mengganti definisi ENUM yang lama dengan yang baru.
        // Pastikan semua nilai ENUM lama ('rented', 'returned', 'late') juga disertakan.
        Schema::table('rentals', function (Blueprint $table) {
            DB::statement("ALTER TABLE rentals MODIFY COLUMN status ENUM('rented', 'returned', 'late', 'waiting_payment', 'cancelled', 'failed') NOT NULL DEFAULT 'rented'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            // Saat rollback, kembalikan ke definisi ENUM yang lama (opsional)
            // Ini akan menyebabkan error jika ada data dengan status 'waiting_payment', 'cancelled', 'failed'
            // di database saat rollback. Jadi hati-hati.
            DB::statement("ALTER TABLE rentals MODIFY COLUMN status ENUM('rented', 'returned', 'late') NOT NULL DEFAULT 'rented'");
        });
    }
};