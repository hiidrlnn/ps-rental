<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Unit::create([
            'name' => 'PlayStation 5',
            'type' => 'Standard Edition',
            'image_url' => 'images/ps4.jpg',
            'code' => 'PS5-001',
            'condition' => 'Excellent',
            'price_per_day' => 75000,
            'stock_available' => 5, // Awalnya ada 5 unit
        ]);

        Unit::create([
            'name' => 'PlayStation 5',
            'type' => 'Digital Edition',
            'image_url' => 'images/ps5.jpg',
            'code' => 'PS5-002',
            'condition' => 'Good',
            'price_per_day' => 70000,
            'stock_available' => 3,
        ]);

        Unit::create([
            'name' => 'PlayStation 4',
            'type' => 'Slim',
            'image_url' => 'images/ps3.jpg',
            'code' => 'PS4-003',
            'condition' => 'Good',
            'price_per_day' => 50000,
            'stock_available' => 8,
        ]);
    }
}