<?php

namespace App\Http\Controllers\Damage;

use App\Http\Controllers\Controller;
use App\Models\Damage\PricingCriteria;
use App\Models\Damage\DamageRecord;
use App\Services\Damage\DamageBaselineService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PricingCriteriaController extends Controller
{
    /**
     * Display listing of pricing criteria.
     */
    public function index(Request $request)
    {
        $criteria = PricingCriteria::orderBy('sector')->orderBy('category')->get();

        $sectors = [
            'real_estate' => 'المباني والوحدات العقارية',
            'infrastructure' => 'البنية التحتية والشبكات',
            'public_facilities' => 'المرافق العامة والحيوية',
            'economic_crafts' => 'القطاع الاقتصادي والحرفي',
            'municipal_assets' => 'ممتلكات وأصول البلدية',
        ];

        return view('damage.pricing.index', compact('criteria', 'sectors'));
    }

    /**
     * Store a newly created pricing criterion.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'sector' => 'required|in:real_estate,infrastructure,public_facilities,economic_crafts,municipal_assets',
            'category' => 'required|string|max:100',
            'unit_type' => 'nullable|string|max:50',
            'unit_of_measure' => 'nullable|string|max:50',
            'unit_price_usd' => 'required|numeric|min:0',
            'unit_price_ils' => 'required|numeric|min:0',
            'damage_multipliers' => 'nullable|array',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $unitOfMeasure = $validated['unit_of_measure'] ?? $validated['unit_type'] ?? 'sqm';
        $notes = $validated['notes'] ?? $validated['description'] ?? null;

        if (empty($validated['damage_multipliers'])) {
            $validated['damage_multipliers'] = [
                'total_destruction' => 1.0,
                'severe_partial' => 0.65,
                'moderate_partial' => 0.35,
                'minor' => 0.15,
            ];
        }

        $criterion = PricingCriteria::create([
            'name_ar' => $validated['name_ar'],
            'name_en' => $validated['name_en'] ?? null,
            'sector' => $validated['sector'],
            'category' => $validated['category'],
            'unit_of_measure' => $unitOfMeasure,
            'unit_price_usd' => (float) $validated['unit_price_usd'],
            'unit_price_ils' => (float) $validated['unit_price_ils'],
            'damage_multipliers' => $validated['damage_multipliers'],
            'notes' => $notes,
            'is_active' => true,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('damage.pricing.index')
            ->with('success', 'تمت إضافة المعيار الهندسي والتسعيري بنجاح: ' . $criterion->name_ar);
    }

    /**
     * Update existing criterion.
     */
    public function update(Request $request, $id)
    {
        $criterion = PricingCriteria::findOrFail($id);

        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'unit_type' => 'nullable|string|max:50',
            'unit_of_measure' => 'nullable|string|max:50',
            'unit_price_usd' => 'required|numeric|min:0',
            'unit_price_ils' => 'required|numeric|min:0',
            'damage_multipliers' => 'nullable|array',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $updateData = [
            'name_ar' => $validated['name_ar'],
            'unit_price_usd' => (float) $validated['unit_price_usd'],
            'unit_price_ils' => (float) $validated['unit_price_ils'],
            'updated_by' => Auth::id(),
        ];

        if (array_key_exists('name_en', $validated)) {
            $updateData['name_en'] = $validated['name_en'];
        }

        if (!empty($validated['unit_of_measure'])) {
            $updateData['unit_of_measure'] = $validated['unit_of_measure'];
        } elseif (!empty($validated['unit_type'])) {
            $updateData['unit_of_measure'] = $validated['unit_type'];
        }

        if (array_key_exists('notes', $validated)) {
            $updateData['notes'] = $validated['notes'];
        } elseif (array_key_exists('description', $validated)) {
            $updateData['notes'] = $validated['description'];
        }

        if (!empty($validated['damage_multipliers'])) {
            $multipliers = [];
            foreach ($validated['damage_multipliers'] as $k => $v) {
                $multipliers[$k] = (float) $v;
            }
            $updateData['damage_multipliers'] = $multipliers;
        }

        if ($request->has('is_active')) {
            $updateData['is_active'] = $request->boolean('is_active');
        }

        $criterion->update($updateData);

        return redirect()->route('damage.pricing.index')
            ->with('success', 'تم تعديل المعيار الهندسي: ' . $criterion->name_ar . ' (السعر: $' . number_format($criterion->unit_price_usd, 2) . ' / ₪' . number_format($criterion->unit_price_ils, 2) . ')');
    }

    /**
     * Bulk recalculate all records tied to this criterion or sector.
     */
    public function bulkRecalculate(Request $request, $id, DamageBaselineService $baselineService)
    {
        $criterion = PricingCriteria::findOrFail($id);

        $records = DamageRecord::where('pricing_criteria_id', $criterion->id)
            ->orWhere('sector', $criterion->sector)
            ->get();

        $count = 0;
        foreach ($records as $record) {
            $baselineService->recalculateCost($record);
            $count++;
        }

        return redirect()->route('damage.pricing.index')
            ->with('success', "تمت إعادة احتساب التكاليف والكميات بنجاح لـ ({$count}) سجلاً بناء على المعيار: {$criterion->name_ar}");
    }
}
