<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Itinerary extends Model
{
    use HasUuids;

    protected $fillable = [
        'package_id', 'day_number', 'title',
        'description', 'location', 'meals_included', 'accommodation',
    ];

    protected $casts = [
        'meals_included' => 'array',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
