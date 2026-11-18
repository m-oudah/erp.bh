<?php

namespace App\Http\Controllers\Buildings;

use App\Http\Controllers\Controller;
use App\Models\Buildings\BhmBuilding;
use App\Models\Buildings\BhmBuildingOwner;
use App\Models\Buildings\BhmCustomer;
use App\Models\Buildings\BhmFloorDescription;
use App\Models\Buildings\BhmStreet;
use App\Models\Buildings\BhmUnit;
use App\Models\Buildings\BhmUnitOwner;
use App\Models\Buildings\BhmUnitUser;
use Illuminate\Http\Request;

class BhmUnitController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only([
            'search', 'unit_number', 'building_id', 'building_number',
            'street_number', 'floor_number', 'unit_type', 'usage',
            'id_card', 'owner_name'
        ]);

        $query = BhmUnit::with([
            'building.street',
            'building.zone',
            'building.owners',
            'floor',
            'unitOwners',
            'buildingOwner',
            'owners'
        ])->filter($filters);

        $units = $query->orderBy('id', 'desc')->paginate(20)->withQueryString();

        $stats = [
            'total_units' => BhmUnit::count(),
            'residential' => BhmUnit::where('residential', 1)->count(),
            'commercial' => BhmUnit::where('commercial', 1)->count(),
            'industrial' => BhmUnit::where('industrial', 1)->count(),
            'other_usages' => BhmUnit::where(function ($q) {
                $q->where('educational', 1)
                  ->orWhere('cultural', 1)
                  ->orWhere('health', 1)
                  ->orWhere('religous', 1)
                  ->orWhere('other_usage', 1);
            })->count(),
        ];

        $streets = BhmStreet::orderByRaw("CASE WHEN street_name IS NOT NULL AND street_name != '' THEN 0 ELSE 1 END, street_name ASC")->take(100)->get();

        return view('buildings.units.index', compact('units', 'filters', 'stats', 'streets'));
    }

    public function create(Request $request)
    {
        $buildingId = $request->input('building_id');
        $selectedBuilding = null;
        $floors = collect([]);
        $buildingOwners = collect([]);

        if ($buildingId) {
            $selectedBuilding = BhmBuilding::with(['street', 'zone', 'owners', 'floors'])->find($buildingId);
            if ($selectedBuilding) {
                $floors = $selectedBuilding->floors;
                $buildingOwners = $selectedBuilding->owners;
            }
        }

        $streets = BhmStreet::orderBy('street_name')->take(100)->get();

        return view('buildings.units.create', compact('selectedBuilding', 'floors', 'buildingOwners', 'streets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'building_id' => 'required|exists:bhm_buildings,id',
            'unit_number' => 'required|string|max:50',
            'unit_name' => 'nullable|string|max:255',
            'floor_number' => 'nullable|integer',
            'floor_id' => 'nullable|exists:bhm_floor_descriptions,id',
            'unit_type' => 'nullable|integer',
            'ownership_type' => 'nullable|integer',
            'current_situation' => 'nullable|integer',
            'profession_name' => 'nullable|string|max:255',
            'profession_type' => 'nullable|string|max:255',
            'unit_notes' => 'nullable|string|max:1000',
            'unit_usage_notes' => 'nullable|string|max:1000',
            'building_owner_id' => 'nullable|exists:bhm_building_owners,id',
            'owner_id_card' => 'nullable|string|max:20',
            'owner_first_name' => 'nullable|string|max:100',
            'owner_second_name' => 'nullable|string|max:100',
            'owner_third_name' => 'nullable|string|max:100',
            'owner_sur_name' => 'nullable|string|max:100',
            'owner_phone' => 'nullable|string|max:30',
        ], [
            'building_id.required' => 'يرجى اختيار المبنى الذي تتبع له هذه الوحدة.',
            'unit_number.required' => 'رقم الوحدة مطلوب.',
        ]);

        $building = BhmBuilding::findOrFail($request->input('building_id'));

        $unitData = $request->only([
            'building_id', 'floor_id', 'unit_number', 'unit_name',
            'floor_number', 'unit_type', 'ownership_type', 'current_situation',
            'profession_name', 'profession_type', 'unit_notes', 'unit_usage_notes',
            'building_owner_id'
        ]);

        // Copy building and street numbers if available
        $unitData['building_number'] = $building->building_number;
        $unitData['street_number'] = $building->street_id ?? $building->street?->street_number;
        $unitData['creation_date'] = now()->toDateString();
        $unitData['created_by'] = auth()->id() ?? 1;

        // Boolean usage flags
        $usageFlags = [
            'residential', 'commercial', 'industrial', 'educational',
            'cultural', 'health', 'tourism', 'religous', 'institutions', 'other_usage'
        ];
        foreach ($usageFlags as $flag) {
            $unitData[$flag] = $request->boolean($flag) ? 1 : 0;
        }

        // If unit_type is 1 (residential) and no specific usage checked, set residential = 1
        if ($request->input('unit_type') == 1 && !array_filter(array_map(fn($f) => $request->boolean($f), $usageFlags))) {
            $unitData['residential'] = 1;
        }

        $unit = BhmUnit::create($unitData);

        // Link with building owner if selected
        if ($request->filled('building_owner_id')) {
            $unit->owners()->syncWithoutDetaching([$request->input('building_owner_id')]);
        }

        // Create or sync direct unit owner in bhm_unit_owners if owner details provided
        if ($request->filled('owner_id_card') || $request->filled('owner_first_name')) {
            $unitOwner = BhmUnitOwner::create([
                'unit_id' => $unit->id,
                'building_owner_id' => $request->input('building_owner_id'),
                'id_card' => trim($request->input('owner_id_card', '')),
                'first_name' => $request->input('owner_first_name', ''),
                'second_name' => $request->input('owner_second_name', ''),
                'third_name' => $request->input('owner_third_name', ''),
                'sur_name' => $request->input('owner_sur_name', ''),
                'phone_number' => $request->input('owner_phone', ''),
                'notes' => $request->input('owner_notes', ''),
            ]);

            // Sync with Unified Citizen Registry (bhm_customers)
            if ($unitOwner->id_card) {
                BhmCustomer::updateOrCreate(
                    ['id_no' => $unitOwner->id_card],
                    [
                        'name' => $unitOwner->full_name ?: 'مالك وحدة',
                        'mobile' => $unitOwner->phone_number ?: '',
                    ]
                );
            }
        }

        return redirect()->route('buildings.units.show', $unit->id)
            ->with('success', "تم إضافة الوحدة ({$unit->display_name}) وربطها بالمبنى رقم {$building->display_name} بنجاح.");
    }

    public function show($id)
    {
        $unit = BhmUnit::with([
            'building.zone',
            'building.subzone',
            'building.street',
            'building.buildingType',
            'building.owners',
            'floor',
            'unitOwners',
            'unitUser',
            'buildingOwner',
            'owners'
        ])->findOrFail($id);

        return view('buildings.units.show', compact('unit'));
    }

    public function edit($id)
    {
        $unit = BhmUnit::with([
            'building.street',
            'building.zone',
            'building.owners',
            'building.floors',
            'floor',
            'unitOwners',
            'unitUser',
            'buildingOwner'
        ])->findOrFail($id);

        $selectedBuilding = $unit->building;
        $floors = $selectedBuilding ? $selectedBuilding->floors : collect([]);
        $buildingOwners = $selectedBuilding ? $selectedBuilding->owners : collect([]);
        $unitOwner = $unit->unitOwners->first();

        return view('buildings.units.edit', compact('unit', 'selectedBuilding', 'floors', 'buildingOwners', 'unitOwner'));
    }

    public function update(Request $request, $id)
    {
        $unit = BhmUnit::findOrFail($id);

        $request->validate([
            'building_id' => 'required|exists:bhm_buildings,id',
            'unit_number' => 'required|string|max:50',
            'unit_name' => 'nullable|string|max:255',
            'floor_number' => 'nullable|integer',
            'floor_id' => 'nullable|exists:bhm_floor_descriptions,id',
            'unit_type' => 'nullable|integer',
            'ownership_type' => 'nullable|integer',
            'current_situation' => 'nullable|integer',
            'profession_name' => 'nullable|string|max:255',
            'profession_type' => 'nullable|string|max:255',
            'unit_notes' => 'nullable|string|max:1000',
            'unit_usage_notes' => 'nullable|string|max:1000',
            'building_owner_id' => 'nullable|exists:bhm_building_owners,id',
            'owner_id_card' => 'nullable|string|max:20',
            'owner_first_name' => 'nullable|string|max:100',
            'owner_second_name' => 'nullable|string|max:100',
            'owner_third_name' => 'nullable|string|max:100',
            'owner_sur_name' => 'nullable|string|max:100',
            'owner_phone' => 'nullable|string|max:30',
        ]);

        $unitData = $request->only([
            'building_id', 'floor_id', 'unit_number', 'unit_name',
            'floor_number', 'unit_type', 'ownership_type', 'current_situation',
            'profession_name', 'profession_type', 'unit_notes', 'unit_usage_notes',
            'building_owner_id'
        ]);

        // Usage flags
        $usageFlags = [
            'residential', 'commercial', 'industrial', 'educational',
            'cultural', 'health', 'tourism', 'religous', 'institutions', 'other_usage'
        ];
        foreach ($usageFlags as $flag) {
            $unitData[$flag] = $request->boolean($flag) ? 1 : 0;
        }

        $unit->update($unitData);

        // Sync building owner
        if ($request->filled('building_owner_id')) {
            $unit->owners()->sync([$request->input('building_owner_id')]);
        }

        // Update or create direct unit owner
        if ($request->filled('owner_id_card') || $request->filled('owner_first_name')) {
            $unitOwner = $unit->unitOwners()->first();
            $ownerPayload = [
                'unit_id' => $unit->id,
                'building_owner_id' => $request->input('building_owner_id'),
                'id_card' => trim($request->input('owner_id_card', '')),
                'first_name' => $request->input('owner_first_name', ''),
                'second_name' => $request->input('owner_second_name', ''),
                'third_name' => $request->input('owner_third_name', ''),
                'sur_name' => $request->input('owner_sur_name', ''),
                'phone_number' => $request->input('owner_phone', ''),
                'notes' => $request->input('owner_notes', ''),
            ];

            if ($unitOwner) {
                $unitOwner->update($ownerPayload);
            } else {
                $unitOwner = BhmUnitOwner::create($ownerPayload);
            }

            // Sync with Unified Citizen Registry
            if ($unitOwner->id_card) {
                BhmCustomer::updateOrCreate(
                    ['id_no' => $unitOwner->id_card],
                    [
                        'name' => $unitOwner->full_name ?: 'مالك وحدة',
                        'mobile' => $unitOwner->phone_number ?: '',
                    ]
                );
            }
        }

        return redirect()->route('buildings.units.show', $unit->id)
            ->with('success', "تم تحديث بيانات الوحدة ({$unit->display_name}) بنجاح.");
    }

    public function destroy($id)
    {
        $unit = BhmUnit::findOrFail($id);
        $displayName = $unit->display_name;

        $unit->owners()->detach();
        $unit->unitOwners()->delete();
        $unit->unitUser()?->delete();
        $unit->delete();

        return redirect()->route('buildings.units.index')
            ->with('success', "تم حذف الوحدة ({$displayName}) بنجاح.");
    }

    /**
     * AJAX Search for Buildings (for autocomplete in create/edit unit forms)
     */
    public function searchBuildings(Request $request)
    {
        $q = trim($request->input('q', ''));
        if (mb_strlen($q) < 1) {
            return response()->json(['status' => 'success', 'data' => []]);
        }

        $buildings = BhmBuilding::with(['street', 'owners', 'floors'])
            ->where(function ($query) use ($q) {
                $query->where('building_number', 'like', "%{$q}%")
                      ->orWhere('file_number', 'like', "%{$q}%")
                      ->orWhere('building_name', 'like', "%{$q}%")
                      ->orWhere('block_number', 'like', "%{$q}%")
                      ->orWhere('parcel_number', 'like', "%{$q}%")
                      ->orWhereHas('owners', function ($oq) use ($q) {
                          $oq->where('id_card', 'like', "%{$q}%")
                             ->orWhere('first_name', 'like', "%{$q}%")
                             ->orWhere('sur_name', 'like', "%{$q}%");
                      });
            })
            ->take(20)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $buildings->map(fn($b) => [
                'id' => $b->id,
                'building_number' => $b->building_number,
                'file_number' => $b->file_number,
                'display_name' => $b->display_name,
                'street_name' => $b->street?->street_name,
                'floors' => $b->floors->map(fn($f) => ['id' => $f->id, 'name' => $f->floor_name]),
                'owners' => $b->owners->map(fn($o) => ['id' => $o->id, 'name' => $o->full_name, 'id_card' => $o->id_card, 'phone' => $o->phone_number]),
            ]),
        ]);
    }
}

