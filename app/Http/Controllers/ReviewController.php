<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Booking;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user', 'package'])
            ->latest()
            ->paginate(15);
        return view('reviews.index', compact('reviews'));
    }

    public function myReviews()
    {
        $reviews = Review::with('package')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(15);
        return view('reviews.my-reviews', compact('reviews'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|string|exists:bookings,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string|max:2000',
        ]);

        $booking = Booking::findOrFail($validated['booking_id']);

        // Must own the booking
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Must be completed
        if ($booking->status !== 'completed') {
            return back()->withErrors(['booking_id' => 'You can only review completed bookings.']);
        }

        // Must not have already reviewed this package
        $existing = Review::where('user_id', auth()->id())
            ->where('package_id', $booking->package_id)
            ->exists();

        if ($existing) {
            return back()->withErrors(['booking_id' => 'You have already reviewed this package.']);
        }

        Review::create([
            'user_id'    => auth()->id(),
            'package_id' => $booking->package_id,
            'rating'     => $validated['rating'],
            'comment'    => $validated['comment'] ?? null,
        ]);

        return back()->with('success', 'Your review has been submitted. Thank you for your feedback!');
    }

    public function edit(Review $review)
    {
        // Ensure the user owns this review
        if ($review->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        // Ensure the user owns this review
        if ($review->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
        ]);

        $review->update([
            'rating'  => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return redirect()->route('reviews.my')
            ->with('success', 'Your review has been updated.');
    }

    public function destroy(Review $review)
    {
        // Only the review owner can delete
        if ($review->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $review->delete();
        return back()->with('success', 'Your review has been deleted.');
    }
}
