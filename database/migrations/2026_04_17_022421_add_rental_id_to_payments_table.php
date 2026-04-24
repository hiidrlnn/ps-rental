<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {

            // tambah kolom rental_id
            $table->unsignedBigInteger('rental_id')->nullable()->after('id');

            // relasi ke tabel rentals
            $table->foreign('rental_id')
                  ->references('id')
                  ->on('rentals')
                  ->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {

            // hapus foreign key dulu
            $table->dropForeign(['rental_id']);

            // hapus kolom
            $table->dropColumn('rental_id');

        });
    }
};