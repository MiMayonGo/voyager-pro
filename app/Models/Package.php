<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasUuids, SoftDeletes;

   
    protected $fillable = [
        'title', 'slug', 'description', 'price',
        'duration_days', 'available_slots',
        'cover_image', 'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function itineraries()
    {
        return $this->hasMany(Itinerary::class)->orderBy('day_number');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
