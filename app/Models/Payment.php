<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Payment extends Model
{
    use HasUuids;

    protected $fillable = [
        'booking_id', 'amount', 'currency', 'gateway',
        'transaction_id', 'status', 'gateway_response', 'paid_at',
    ];

    protected $casts = [
        'gateway_response' => 'array',
        'paid_at'          => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}