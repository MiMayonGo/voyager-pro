<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Invoice extends Model
{
    use HasUuids;

    protected $fillable = [
        'booking_id', 'invoice_number',
        'subtotal', 'tax', 'total', 'issued_at', 'due_date',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'due_date'  => 'date',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
