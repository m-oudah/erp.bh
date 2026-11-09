<?php

namespace App\Http\Controllers\Buildings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Buildings\BhmEconomical;
use App\Models\Buildings\BhmEconomicalSector;
use App\Models\Buildings\BhmEconomicalOwner;
use App\Models\Buildings\BhmCraftType;
use App\Models\Buildings\BhmCraftTypeEconomical;
use App\Models\Buildings\BhmCraftStatus;
use App\Models\Buildings\BhmCustomer;
use App\Models\Buildings\BhmBuilding;
use App\Models\Buildings\BhmUnit;

class BhmEconomicalController extends Controller
{
    /**
     * Display a listing of economical activities / crafts.
     */
    public function index(Request $request)
    {
        $filters = $request->only([
            'id_card', 'name', 'trade_name', 'job_sector_id', 'craft_type_id', 'isLicensed', 'isDanger'
        ]);

        $sort = $request->get('sort', 'sector_first');
        $query = BhmEconomical::with(['building', 'sector', 'category', 'owners', 'crafts.craftType', 'crafts.status'])
            ->filter($filters);

        if ($sort === 'id_asc') {
            $query->orderBy('id', 'asc');
        } elseif ($sort === 'id_desc') {
            $query->orderBy('id', 'desc');
        } else {
            $query->orderByRaw('CASE WHEN job_sector_id IS NOT NULL THEN 0 ELSE 1 END')
                  ->orderBy('id', 'desc');
        }

        $economicals = $query->paginate(15)->withQueryString();

        $sectors = BhmEconomicalSector::orderBy('name')->get();
        $craftTypes = BhmCraftType::orderBy('name')->get();

        // Pre-load customers keyed by id_no to avoid N+1 in the view
        $ownerCards = $economicals->flatMap(function($e) {
            $cards = $e->owners->pluck('id_card')->toArray();
            if ($e->id_card) $cards[] = $e->id_card;
            return $cards;
        })->filter()->unique()->values();
        $customersMap = BhmCustomer::whereIn('id_no', $ownerCards)->get()->keyBy('id_no');

        return view('buildings.economical.index', compact('economicals', 'filters', 'sectors', 'craftTypes', 'customersMap'));
    }

    /**
     * Show form for creating a new economical activity / craft.
     */
    public function create()
    {
        $sectors = BhmEconomicalSector::orderBy('name')->get();
        $craftTypes = BhmCraftType::orderBy('name')->get();

        return view('buildings.economical.create', compact('sectors', 'craftTypes'));
    }

    /**
     * Store a newly created economical activity / craft.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_formal_name' => 'required|string|max:255',
            'trade_name'      => 'nullable|string|max:255',
            'job_sector_id'   => 'nullable|integer|exists:bhm_economical_sectors,id',
            'craft_type_id'   => 'nullable|integer|exists:bhm_craft_types,id',
            'building_id'     => 'nullable|integer|exists:bhm_buildings,id',
            'unit_id'         => 'nullable|integer',
            'id_card'         => 'nullable|string|max:20',
            'owner_name'      => 'nullable|string|max:255',
            'isLicensed'      => 'nullable|in:1,2',
            'license_number'  => 'nullable|string|max:100',
            'craft_number'    => 'nullable|string|max:100',
            'file_number'     => 'nullable|string|max:100',
            'isDanger'        => 'nullable|in:1,0',
            'isActive'        => 'nullable|in:1,2',
            'work_state'      => 'nullable|in:0,1,2',
            'job_phone'       => 'nullable|string|max:50',
            'job_mobile'      => 'nullable|string|max:50',
            'whatsapp'        => 'nullable|string|max:50',
            'email'           => 'nullable|string|max:100',
            'job_location'    => 'nullable|string|max:255',
            'job_street'      => 'nullable|string|max:255',
            'job_description' => 'nullable|string',
            'notes'           => 'nullable|string',
        ], [
            'job_formal_name.required' => 'يرجى إدخال الاسم الرسمي للنشاط / الحرفة.',
            'job_sector_id.exists'     => 'القطاع الاقتصادي المحدد غير صالح.',
            'craft_type_id.exists'     => 'نوع الحرفة المحدد غير صالح.',
            'building_id.exists'       => 'المبنى المحدد غير موجود في النظام.',
        ]);

        return DB::transaction(function () use ($validated) {
            $building = null;
            if (!empty($validated['building_id'])) {
                $building = BhmBuilding::find($validated['building_id']);
            }

            $craftType = null;
            if (!empty($validated['craft_type_id'])) {
                $craftType = BhmCraftType::find($validated['craft_type_id']);
            }

            $data = [
                'job_formal_name'   => $validated['job_formal_name'],
                'trade_name'        => $validated['trade_name'] ?? null,
                'job_sector_id'     => $validated['job_sector_id'] ?? null,
                'craft_type_id'     => $validated['craft_type_id'] ?? null,
                'craft_category_id' => $craftType?->category_id ?? null,
                'building_id'       => $validated['building_id'] ?? null,
                'unit_id'           => $validated['unit_id'] ?? null,
                'id_card'           => $validated['id_card'] ?? null,
                'name'              => $validated['owner_name'] ?? null,
                'isLicensed'        => $validated['isLicensed'] ?? 2,
                'license_number'    => $validated['license_number'] ?? null,
                'craft_number'      => $validated['craft_number'] ?? null,
                'file_number'       => $validated['file_number'] ?? null,
                'isDanger'          => $validated['isDanger'] ?? 0,
                'isActive'          => $validated['isActive'] ?? 2,
                'work_state'        => $validated['work_state'] ?? 1,
                'job_phone'         => $validated['job_phone'] ?? null,
                'job_mobile'        => $validated['job_mobile'] ?? null,
                'whatsapp'          => $validated['whatsapp'] ?? null,
                'email'             => $validated['email'] ?? null,
                'job_location'      => $validated['job_location'] ?? null,
                'job_street'        => $validated['job_street'] ?? null,
                'job_description'   => $validated['job_description'] ?? null,
                'notes'             => $validated['notes'] ?? null,
                'created_by'        => auth()->id(),
            ];

            // Auto populate building geographical data if building selected
            if ($building) {
                $data['building_number'] = $building->building_number;
                $data['zone_id']         = $building->zone_id;
                $data['subzone_id']      = $building->subzone_id;
                $data['street_id']       = $building->street_id;
                $data['parcel_number']   = $building->parcel_number;
                $data['block_number']    = $building->block_number;
            }

            $economical = BhmEconomical::create($data);

            // Create owner record in bhm_economical_owners if owner info provided
            if (!empty($validated['id_card']) || !empty($validated['owner_name'])) {
                $nameParts = preg_split('/\s+/', trim($validated['owner_name'] ?? ''));
                BhmEconomicalOwner::create([
                    'economical_id' => $economical->id,
                    'id_card'       => $validated['id_card'] ?? null,
                    'first_name'    => $nameParts[0] ?? '',
                    'second_name'   => $nameParts[1] ?? '',
                    'third_name'    => $nameParts[2] ?? '',
                    'sur_name'      => isset($nameParts[3]) ? implode(' ', array_slice($nameParts, 3)) : '',
                    'phone'         => $validated['job_mobile'] ?? ($validated['job_phone'] ?? ''),
                    'phone_number'  => $validated['job_mobile'] ?? ($validated['job_phone'] ?? '0'),
                ]);
            }

            // Create craft type linkage if selected
            if ($craftType) {
                BhmCraftTypeEconomical::create([
                    'economical_id'     => $economical->id,
                    'craft_type_id'     => $craftType->id,
                    'craft_category_id' => $craftType->category_id,
                ]);
            }

            return redirect()->route('crafts.economical.show', $economical->id)
                ->with('success', 'تم إضافة النشاط الاقتصادي / الحرفة بنجاح.');
        });
    }

    /**
     * Display the specified economical activity / craft.
     */
    public function show($id)
    {
        $economical = BhmEconomical::with([
            'building.zone', 'building.street', 'unit', 'sector', 'category', 'owners', 
            'crafts.craftType', 'crafts.status', 'crafts.category'
        ])->findOrFail($id);

        return view('buildings.economical.show', compact('economical'));
    }

    /**
     * Show form for editing the economical activity / craft.
     */
    public function edit($id)
    {
        $economical = BhmEconomical::with([
            'building.zone', 'building.street', 'building.units', 'unit', 'sector', 'owners', 'crafts.craftType'
        ])->findOrFail($id);
        
        $sectors = BhmEconomicalSector::orderBy('name')->get();
        $craftTypes = BhmCraftType::orderBy('name')->get();

        $buildingUnits = $economical->building_id 
            ? BhmUnit::where('building_id', $economical->building_id)->orderBy('unit_number')->get() 
            : collect();

        return view('buildings.economical.edit', compact('economical', 'sectors', 'craftTypes', 'buildingUnits'));
    }

    /**
     * Update the specified economical activity / craft.
     */
    public function update(Request $request, $id)
    {
        $economical = BhmEconomical::findOrFail($id);
        
        $validated = $request->validate([
            'job_formal_name' => 'required|string|max:255',
            'trade_name'      => 'nullable|string|max:255',
            'job_sector_id'   => 'nullable|integer|exists:bhm_economical_sectors,id',
            'craft_type_id'   => 'nullable|integer|exists:bhm_craft_types,id',
            'building_id'     => 'nullable|integer|exists:bhm_buildings,id',
            'unit_id'         => 'nullable|integer',
            'id_card'         => 'nullable|string|max:20',
            'owner_name'      => 'nullable|string|max:255',
            'isLicensed'      => 'nullable|in:1,2',
            'license_number'  => 'nullable|string|max:100',
            'craft_number'    => 'nullable|string|max:100',
            'file_number'     => 'nullable|string|max:100',
            'isDanger'        => 'nullable|in:1,0',
            'isActive'        => 'nullable|in:1,2',
            'work_state'      => 'nullable|in:0,1,2',
            'job_phone'       => 'nullable|string|max:50',
            'job_mobile'      => 'nullable|string|max:50',
            'whatsapp'        => 'nullable|string|max:50',
            'email'           => 'nullable|string|max:100',
            'job_location'    => 'nullable|string|max:255',
            'job_street'      => 'nullable|string|max:255',
            'job_description' => 'nullable|string',
            'notes'           => 'nullable|string',
        ], [
            'job_formal_name.required' => 'يرجى إدخال الاسم الرسمي للنشاط / الحرفة.',
            'job_sector_id.exists'     => 'القطاع الاقتصادي المحدد غير صالح.',
            'craft_type_id.exists'     => 'نوع الحرفة المحدد غير صالح.',
            'building_id.exists'       => 'المبنى المحدد غير موجود في النظام.',
        ]);

        return DB::transaction(function () use ($validated, $economical) {
            $building = null;
            if (!empty($validated['building_id'])) {
                $building = BhmBuilding::find($validated['building_id']);
            }

            $craftType = null;
            if (!empty($validated['craft_type_id'])) {
                $craftType = BhmCraftType::find($validated['craft_type_id']);
            }

            $data = [
                'job_formal_name' => $validated['job_formal_name'],
                'trade_name'      => $validated['trade_name'] ?? null,
                'job_sector_id'   => $validated['job_sector_id'] ?? null,
                'craft_type_id'   => $validated['craft_type_id'] ?? null,
                'building_id'     => $validated['building_id'] ?? null,
                'unit_id'         => $validated['unit_id'] ?? null,
                'id_card'         => $validated['id_card'] ?? null,
                'name'            => $validated['owner_name'] ?? null,
                'isLicensed'      => $validated['isLicensed'] ?? 2,
                'license_number'  => $validated['license_number'] ?? null,
                'craft_number'    => $validated['craft_number'] ?? null,
                'file_number'     => $validated['file_number'] ?? null,
                'isDanger'        => $validated['isDanger'] ?? 0,
                'isActive'        => $validated['isActive'] ?? 2,
                'work_state'      => $validated['work_state'] ?? 1,
                'job_phone'       => $validated['job_phone'] ?? null,
                'job_mobile'      => $validated['job_mobile'] ?? null,
                'whatsapp'        => $validated['whatsapp'] ?? null,
                'email'           => $validated['email'] ?? null,
                'job_location'    => $validated['job_location'] ?? null,
                'job_street'      => $validated['job_street'] ?? null,
                'job_description' => $validated['job_description'] ?? null,
                'notes'           => $validated['notes'] ?? null,
            ];

            if ($craftType) {
                $data['craft_category_id'] = $craftType->category_id;
            }

            if ($building) {
                $data['building_number'] = $building->building_number;
                $data['zone_id']         = $building->zone_id;
                $data['subzone_id']      = $building->subzone_id;
                $data['street_id']       = $building->street_id;
                $data['parcel_number']   = $building->parcel_number;
                $data['block_number']    = $building->block_number;
            }

            $economical->update($data);

            // Update or create owner in bhm_economical_owners
            if (!empty($validated['id_card']) || !empty($validated['owner_name'])) {
                $nameParts = preg_split('/\s+/', trim($validated['owner_name'] ?? ''));
                $firstOwner = $economical->owners()->first();
                $ownerData = [
                    'id_card'      => $validated['id_card'] ?? null,
                    'first_name'   => $nameParts[0] ?? '',
                    'second_name'  => $nameParts[1] ?? '',
                    'third_name'   => $nameParts[2] ?? '',
                    'sur_name'     => isset($nameParts[3]) ? implode(' ', array_slice($nameParts, 3)) : '',
                    'phone'        => $validated['job_mobile'] ?? ($validated['job_phone'] ?? ''),
                    'phone_number' => $validated['job_mobile'] ?? ($validated['job_phone'] ?? '0'),
                ];

                if ($firstOwner) {
                    $firstOwner->update($ownerData);
                } else {
                    $ownerData['economical_id'] = $economical->id;
                    BhmEconomicalOwner::create($ownerData);
                }
            }

            // Sync craft type
            if ($craftType) {
                $firstCraft = $economical->crafts()->first();
                if ($firstCraft) {
                    $firstCraft->update([
                        'craft_type_id'     => $craftType->id,
                        'craft_category_id' => $craftType->category_id,
                    ]);
                } else {
                    BhmCraftTypeEconomical::create([
                        'economical_id'     => $economical->id,
                        'craft_type_id'     => $craftType->id,
                        'craft_category_id' => $craftType->category_id,
                    ]);
                }
            }

            return redirect()->route('crafts.economical.show', $economical->id)
                ->with('success', 'تم تحديث بيانات النشاط الاقتصادي / الحرفة بنجاح.');
        });
    }

    /**
     * AJAX search buildings by number, id, zone, or street.
     */
    public function searchBuildings(Request $request)
    {
        $q = trim($request->input('q', ''));
        if (mb_strlen($q) < 1) {
            return response()->json(['status' => 'success', 'data' => []]);
        }

        $buildings = BhmBuilding::with(['zone', 'street'])
            ->where(function ($query) use ($q) {
                $query->where('building_number', 'like', "%{$q}%")
                      ->orWhere('id', $q)
                      ->orWhereHas('street', fn($sq) => $sq->where('street_name', 'like', "%{$q}%"))
                      ->orWhereHas('zone', fn($zq) => $zq->where('name', 'like', "%{$q}%"));
            })
            ->limit(15)
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $buildings->map(fn($b) => [
                'id'              => $b->id,
                'building_number' => $b->building_number ?? $b->id,
                'zone_name'       => $b->zone->name ?? '',
                'street_name'     => $b->street->street_name ?? '',
                'label'           => "مبنى رقم #{$b->building_number} (" . ($b->zone->name ?? 'غير محدد') . ' - ' . ($b->street->street_name ?? '') . ")",
            ]),
        ]);
    }

    /**
     * AJAX search citizens by ID card, name, or phone.
     */
    public function searchCitizens(Request $request)
    {
        $q = trim($request->input('q', ''));
        if (mb_strlen($q) < 2) {
            return response()->json(['status' => 'success', 'data' => []]);
        }

        $citizens = BhmCustomer::where(function ($query) use ($q) {
            $query->where('id_no', 'like', "{$q}%")
                  ->orWhere('name', 'like', "%{$q}%")
                  ->orWhere('mobile', 'like', "%{$q}%");
        })
        ->limit(10)
        ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $citizens->map(fn($c) => [
                'id_no'  => $c->id_no,
                'name'   => $c->name,
                'mobile' => $c->mobile ?: $c->telephone,
            ]),
        ]);
    }

    /**
     * AJAX get units of a building.
     */
    public function getBuildingUnits($buildingId)
    {
        $units = BhmUnit::where('building_id', $buildingId)
            ->orderBy('unit_number')
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $units->map(fn($u) => [
                'id'          => $u->id,
                'unit_number' => $u->unit_number,
                'unit_name'   => $u->unit_name ?: "وحدة رقم {$u->unit_number}",
                'unit_type'   => $u->unit_type_label,
            ]),
        ]);
    }
}
