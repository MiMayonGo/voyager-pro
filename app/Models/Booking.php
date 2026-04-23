<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id', 'package_id', 'slots_booked',
        'total_price', 'status', 'travel_date',
        'special_requests', 'cancelled_at',
    ];

    protected $casts = [
        'travel_date'  => 'date',
        'cancelled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
