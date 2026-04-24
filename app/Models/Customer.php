<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // Pastikan 'email' ada di dalam array $fillable
    protected $fillable = ['name', 'address', 'phone', 'email'];

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}