<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LocationController extends Controller
{
    public function index()
    {
        $locations  = Location::orderBy('created_at', 'desc')->paginate(15);
        $categories = Category::orderBy('sort_order')->orderBy('name')->get()->keyBy('name');
        return view('admin.locations.index', compact('locations', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.locations.form', ['location' => null, 'categories' => $categories]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'address'     => 'nullable|string|max:255',
            'category'    => 'nullable|string|max:100',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'latitude'    => 'nullable|numeric|between:-90,90',
            'longitude'   => 'nullable|numeric|between:-180,180',
            'is_active'   => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('locations', 'public');
            $validated['image'] = $path;
        }

        Location::create($validated);

        return redirect()->route('admin.locations.index')->with('success', 'Địa điểm đã được thêm thành công!');
    }

    public function show(Location $location)
    {
        return redirect()->route('admin.locations.edit', $location);
    }

    public function edit(Location $location)
    {
        $categories = Category::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.locations.form', compact('location', 'categories'));
    }

    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'address'     => 'nullable|string|max:255',
            'category'    => 'nullable|string|max:100',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'latitude'    => 'nullable|numeric|between:-90,90',
            'longitude'   => 'nullable|numeric|between:-180,180',
            'is_active'   => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($location->image) {
                Storage::disk('public')->delete($location->image);
            }
            $path = $request->file('image')->store('locations', 'public');
            $validated['image'] = $path;
        }

        $location->update($validated);

        return redirect()->route('admin.locations.index')->with('success', 'Địa điểm đã được cập nhật thành công!');
    }

    public function destroy(Location $location)
    {
        if ($location->image) {
            Storage::disk('public')->delete($location->image);
        }
        $location->delete();

        return redirect()->route('admin.locations.index')->with('success', 'Địa điểm đã được xóa thành công!');
    }
}
