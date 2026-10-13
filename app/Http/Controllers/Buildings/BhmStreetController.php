<?php

namespace App\Http\Controllers\Buildings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buildings\BhmStreet;

class BhmStreetController extends Controller
{
    public function index(Request $request)
    {
        $query = BhmStreet::withCount('buildings');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('street_name', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('street_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('filter_type')) {
            if ($request->filter_type === 'named') {
                $query->whereNotNull('street_name')->where('street_name', '!=', '');
            } elseif ($request->filter_type === 'unnamed') {
                $query->where(function ($q) {
                    $q->whereNull('street_name')->orWhere('street_name', '');
                });
            } elseif ($request->filter_type === 'with_buildings') {
                $query->has('buildings');
            } elseif ($request->filter_type === 'no_buildings') {
                $query->doesntHave('buildings');
            }
        }

        $sort = $request->get('sort', 'number_asc');
        switch ($sort) {
            case 'number_desc':
                $query->orderBy('street_number', 'desc');
                break;
            case 'name_asc':
                $query->orderByRaw("CASE WHEN street_name IS NOT NULL AND street_name != '' THEN 0 ELSE 1 END")
                      ->orderBy('street_name', 'asc');
                break;
            case 'buildings_desc':
                $query->orderBy('buildings_count', 'desc');
                break;
            case 'number_asc':
            default:
                $query->orderBy('street_number', 'asc');
                break;
        }

        $streets = $query->paginate(20)->withQueryString();

        // Calculate quick summary stats
        $stats = [
            'total'          => BhmStreet::count(),
            'named'          => BhmStreet::whereNotNull('street_name')->where('street_name', '!=', '')->count(),
            'unnamed'        => BhmStreet::where(function ($q) {
                                    $q->whereNull('street_name')->orWhere('street_name', '');
                                })->count(),
            'with_buildings' => BhmStreet::has('buildings')->count(),
        ];

        return view('buildings.streets.index', compact('streets', 'stats'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'street_name' => 'nullable|string|max:255',
        ]);

        $street = BhmStreet::findOrFail($id);
        $street_name = trim($request->input('street_name', ''));

        $street->street_name = $street_name !== '' ? $street_name : null;
        $street->name = $street_name !== '' ? $street_name : null;
        $street->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'       => true,
                'message'       => 'تم تحديث اسم الشارع بنجاح',
                'street_id'     => $street->id,
                'street_number' => $street->street_number,
                'street_name'   => $street->street_name,
            ]);
        }

        return back()->with('success', 'تم تحديث اسم الشارع بنجاح');
    }
}
