<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
 public function index(Request $request)
{
    $search = $request->input('search');

    if (auth()->user()->hasRole('customer')) {
        $bookings = Booking::with('package')
            ->where('user_id', auth()->id())
            ->when($search, fn($q) => $q->whereHas('package', fn($p) => $p->where('title', 'like', "%{$search}%")))
            ->latest()
            ->paginate(9)
            ->withQueryString();
    } else {
        $bookings = Booking::with(['user', 'package'])
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->whereHas('package', fn($p) => $p->where('title', 'like', "%{$search}%"))
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
            }))
            ->latest()
            ->paginate(12)
            ->withQueryString();
    }       
    return view('bookings.index', compact('bookings', 'search'));
}
    public function show(Booking $booking)
    {
        if (auth()->id() !== $booking->user_id && !auth()->user()->hasPermissionTo('bookings.view')) {
            abort(403);
        }
        $booking->load('package', 'user', 'payment', 'invoice');
        return view('bookings.show', compact('booking'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'package_id'       => 'required|exists:packages,id',
            'travel_date'      => 'required|date|after:today',
            'slots_booked'     => 'required|integer|min:1',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        $package = Package::findOrFail($validated['package_id']);

        if ($package->available_slots < $validated['slots_booked']) {
            return back()->withErrors(['slots_booked' => 'Not enough available slots.']);
        }

        DB::transaction(function () use ($validated, $package) {
            $booking = Booking::create([
                'user_id'          => auth()->id(),
                'package_id'       => $package->id,
                'slots_booked'     => $validated['slots_booked'],
                'total_price'      => $package->price * $validated['slots_booked'],
                'travel_date'      => $validated['travel_date'],
                'special_requests' => $validated['special_requests'] ?? null,
                'status'           => 'pending',
            ]);

            $package->decrement('available_slots', $validated['slots_booked']);
        });

        return redirect()->route('bookings.index')->with('success', 'Booking created successfully.');
    }

    public function cancel(Booking $booking)
    {
        if (auth()->id() !== $booking->user_id && !auth()->user()->hasPermissionTo('bookings.manage')) {
            abort(403);
        }

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->withErrors(['status' => 'This booking cannot be cancelled.']);
        }

        DB::transaction(function () use ($booking) {
            $booking->update([
                'status'       => 'cancelled',
                'cancelled_at' => now(),
            ]);

            if ($booking->package) {
                $booking->package->increment('available_slots', $booking->slots_booked);
            }
        });

        return back()->with('success', 'Booking cancelled.');
    }

    public function confirm(Booking $booking)
    {
        if (!auth()->user()->hasPermissionTo('bookings.manage')) {
            abort(403);
        }

        if ($booking->status !== 'pending') {
            return back()->withErrors(['status' => 'Only pending bookings can be confirmed.']);
        }

        $booking->update(['status' => 'confirmed']);
        return back()->with('success', 'Booking confirmed.');
    }

    public function complete(Booking $booking)
    {
        if (!auth()->user()->hasPermissionTo('bookings.manage')) {
            abort(403);
        }

        if ($booking->status !== 'confirmed') {
            return back()->withErrors(['status' => 'Only confirmed bookings can be marked as completed.']);
        }

        $booking->update(['status' => 'completed']);
        return back()->with('success', 'Booking marked as completed.');
    }
}