<?php

namespace App\Http\Controllers\Buildings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buildings\BhmBuilding;
use App\Models\Buildings\BhmZone;
use App\Models\Buildings\BhmBuildingType;
use App\Models\Buildings\BhmStreet;
use App\Models\ArchiveFile;

class BhmBuildingController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only([
            'file_number', 'building_number', 'block_number', 'parcel_number',
            'building_name', 'zone_id', 'street_id', 'building_type_id',
            'id_card', 'owner_name'
        ]);

        $buildings = BhmBuilding::with(['zone', 'street', 'buildingType', 'owners'])
            ->filter($filters)
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        $zones = BhmZone::orderBy('name')->get();
        $buildingTypes = BhmBuildingType::orderBy('name')->get();

        // If zone is selected, get its streets for the filter dropdown
        $streets = collect();
        if ($request->filled('zone_id')) {
            $streets = BhmStreet::where('zone_id', $request->zone_id)->orderBy('name')->get();
        } else {
            $streets = BhmStreet::orderBy('name')->get();
        }

        return view('buildings.buildings.index', compact('buildings', 'filters', 'zones', 'buildingTypes', 'streets'));
    }

    public function show($id)
    {
        $building = BhmBuilding::with([
            'zone', 'street', 'subzone', 'buildingType', 'buildingStatus', 'buildingPropertyType',
            'owners', 'floors', 'units', 'financial', 'supervisor', 'uses', 'materials'
        ])->findOrFail($id);

        $archiveFiles = collect();
        if ($building->file_number) {
            $archiveFiles = ArchiveFile::with('fileType')->where('file_no', $building->file_number)->get();
        }

        return view('buildings.buildings.show', compact('building', 'archiveFiles'));
    }
}
