<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'image_url', 
        'code',
        'condition',
        'price_per_day',
        'stock_available',
    ];

    protected $casts = [
        'price_per_day' => 'float',
        'stock_available' => 'integer',
    ];

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}