<?php

namespace App\Http\Controllers\Buildings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buildings\BhmEconomical;
use App\Models\Buildings\BhmEconomicalSector;
use App\Models\Buildings\BhmCraftType;
use App\Models\Buildings\BhmCraftStatus;
use App\Models\Buildings\BhmCustomer;

class BhmEconomicalController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only([
            'id_card', 'name', 'trade_name', 'job_sector_id', 'craft_type_id', 'isLicensed', 'isDanger'
        ]);

        $economicals = BhmEconomical::with(['building', 'sector', 'owners', 'crafts.craftType', 'crafts.status'])
            ->filter($filters)
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

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

    public function show($id)
    {
        $economical = BhmEconomical::with([
            'building.zone', 'building.street', 'unit', 'sector', 'owners', 
            'crafts.craftType', 'crafts.status', 'crafts.category'
        ])->findOrFail($id);

        return view('buildings.economical.show', compact('economical'));
    }

    public function edit($id)
    {
        $economical = BhmEconomical::findOrFail($id);
        $sectors = BhmEconomicalSector::orderBy('name')->get();

        return view('buildings.economical.edit', compact('economical', 'sectors'));
    }

    public function update(Request $request, $id)
    {
        $economical = BhmEconomical::findOrFail($id);
        
        $validated = $request->validate([
            'job_formal_name' => 'nullable|string|max:255',
            'job_sector_id' => 'nullable|integer|exists:bhm_economical_sectors,id',
            'isLicensed' => 'nullable|in:1,2',
            'isDanger' => 'nullable|in:1,0',
            'notes' => 'nullable|string'
        ]);

        $economical->update($validated);

        return redirect()->route('buildings.economical.show', $economical->id)->with('success', 'تم تحديث بيانات النشاط بنجاح');
    }
}
