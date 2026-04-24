<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Rental;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'external_id',
        'rental_id',

        'payment_type',     // rental / fine
        'method',           // manual / xendit

        'amount_paid',
        'change_amount',

        'xendit_invoice_id',
        'invoice_url',
        'payment_channel',

        'status',
    ];

    protected $casts = [
        'amount_paid' => 'integer',
        'change_amount' => 'integer',
    ];

    // 🔥 RELASI KE RENTAL
    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
}