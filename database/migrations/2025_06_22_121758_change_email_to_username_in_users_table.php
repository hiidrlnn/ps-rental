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
        Schema::table('users', function (Blueprint $table) {
            // Hapus unique index dari kolom email jika ada (biasanya bawaan Laravel)
            $table->dropUnique(['email']);

            // Ubah nama kolom email menjadi username
            $table->renameColumn('email', 'username');

            // Jadikan username unique lagi
            $table->unique('username');

            // Jika ingin tetap menyimpan email tapi tidak untuk login, bisa tambahkan kolom email baru yang nullable
            // $table->string('email_address')->nullable()->after('username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Balikkan perubahan jika migrasi di-rollback
            $table->dropUnique(['username']);
            $table->renameColumn('username', 'email');
            $table->unique('email'); // Kembalikan unique ke email
        });
    }
};