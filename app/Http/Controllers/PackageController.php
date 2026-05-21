<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');

        $packages = Package::with('categories')
            ->withCount('reviews as review_count')
            ->withAvg('reviews as avg_rating', 'rating')
            ->when($search, fn($q) => $q->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('price', 'like', "%{$search}%");
            }))
            ->when($category, fn($q) => $q->whereHas('categories', fn($c) => $c->where('categories.id', $category)))
            ->when(auth()->user()->hasRole('customer'), fn($q) => $q->where('status', 'active'))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('packages.index', compact('packages', 'categories', 'search', 'category'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('packages.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'duration_days'    => 'required|integer|min:1',
            'available_slots'  => 'required|integer|min:0',
            'status'           => 'required|in:active,inactive,draft',
            'cover_image'      => 'nullable|image|max:2048',
            'categories'       => 'array',
            'categories.*'     => 'exists:categories,id',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('packages', 'public');
        }

        $package = Package::create($validated);
        $package->categories()->sync($request->input('categories', []));

        return redirect()->route('packages.index')->with('success', 'Package created successfully.');
    }

    public function show(Package $package)
    {
        $package->load('categories', 'itineraries', 'reviews');
        return view('packages.show', compact('package'));
    }

    public function edit(Package $package)
    {
        $categories = Category::orderBy('name')->get();
        $package->load('categories');
        return view('packages.edit', compact('package', 'categories'));
    }

    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'price'           => 'required|numeric|min:0',
            'duration_days'   => 'required|integer|min:1',
            'available_slots' => 'required|integer|min:0',
            'status'          => 'required|in:active,inactive,draft',
            'cover_image'     => 'nullable|image|max:2048',
            'categories'      => 'array',
            'categories.*'    => 'exists:categories,id',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('packages', 'public');
        }

        $package->update($validated);
        $package->categories()->sync($request->input('categories', []));

        return redirect()->route('packages.index')->with('success', 'Package updated successfully.');
    }

    public function destroy(Package $package)
    {
        $package->delete();
        return redirect()->route('packages.index')->with('success', 'Package deleted.');
    }
}