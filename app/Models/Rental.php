<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'unit_id',
        'quantity',
        'duration_days',
        'rental_date',
        'return_date',
        'actual_return_time',
        'subtotal_price',
        'total_price',
        'fine_amount',
        'status',
    ];

    protected $casts = [
        'rental_date' => 'datetime', // <--- UBAH INI DARI 'date' KE 'datetime'
        'return_date' => 'datetime', // <--- UBAH INI DARI 'date' KE 'datetime'
        'actual_return_time' => 'datetime',
    ];

    // Relasi ke Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relasi ke Unit
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    // Relasi ke Payment
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}