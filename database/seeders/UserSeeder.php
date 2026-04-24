<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Pemilik Rental',
            'username' => 'owner.rental', // <--- UBAH INI KE USERNAME
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        User::create([
            'name' => 'Petugas Rental',
            'username' => 'petugas.rental', // <--- UBAH INI KE USERNAME
            'password' => Hash::make('password'),
            'role' => 'petugas',
        ]);
    }
}