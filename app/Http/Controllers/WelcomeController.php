<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Support\Facades\Storage;

class WelcomeController extends Controller
{
    public function index()
    {
        $packages = Package::query()
            ->where('status', 'active')
            ->withCount('reviews as review_count')
            ->withAvg('reviews as avg_rating', 'rating')
            ->with([
                'categories:id,name',
                'itineraries' => fn($q) => $q->orderBy('day_number'),
            ])
            ->orderByDesc('review_count')
            ->limit(5)
            ->get()
            ->map(fn($pkg) => [
                'id'          => $pkg->id,
                'title'       => $pkg->title,
                'slug'        => $pkg->slug,
                'image'       => $pkg->cover_image
                                    ? Storage::url($pkg->cover_image)
                                    : 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600&q=80',
                'rating'      => $pkg->avg_rating ? round((float) $pkg->avg_rating, 1) : null,
                'reviewCount' => (int) $pkg->review_count,
                'duration'    => $pkg->duration_days . 'D/' . ($pkg->duration_days - 1) . 'N',
                'categories'  => $pkg->categories->pluck('name')->all(),
                'price'       => '₱' . number_format((float) $pkg->price, 0),
                'slots'       => $pkg->available_slots,
                'itinerary'   => $pkg->itineraries->map(fn($it) => [
                    'day'   => $it->day_number,
                    'title' => $it->title,
                    'desc'  => $it->description ?? '',
                ])->all(),
            ]);

        return view('welcome', compact('packages'));
    }
}