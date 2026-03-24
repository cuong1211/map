<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Location::where('is_active', true);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $locations = $query->orderBy('name')->paginate(20)->withQueryString();

        $categories = Location::where('is_active', true)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();

        return view('home.index', compact('locations', 'categories'));
    }

    public function map()
    {
        $categories = Location::where('is_active', true)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();

        return view('home.map', compact('categories'));
    }

    public function apiLocations()
    {
        $locations = Location::where('is_active', true)
            ->get()
            ->map(function ($location) {
                return [
                    'id'          => $location->id,
                    'name'        => $location->name,
                    'description' => $location->description,
                    'address'     => $location->address,
                    'category'    => $location->category,
                    'image'       => $location->image ? asset('storage/' . $location->image) : null,
                    'latitude'    => $location->latitude,
                    'longitude'   => $location->longitude,
                ];
            });

        return response()->json(['locations' => $locations]);
    }
}
