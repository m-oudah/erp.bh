<?php

namespace App\Http\Controllers\Damage;

use App\Http\Controllers\Controller;
use App\Models\Damage\DamageRecord;
use App\Models\Damage\PricingCriteria;
use App\Models\Damage\DonorOrganization;
use App\Models\Buildings\BhmZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DamageDashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Overall Totals
        $totalRecords = DamageRecord::count();
        $totalCostUsd = DamageRecord::sum('estimated_cost_usd');
        $totalCostIls = DamageRecord::sum('estimated_cost_ils');
        $totalApproved = DamageRecord::where('workflow_status', 'final_approved')->count();
        $totalReview = DamageRecord::where('workflow_status', 'engineering_review')->count();
        $totalDraft = DamageRecord::where('workflow_status', 'draft')->count();

        // 2. Sectoral Breakdown
        $sectorStats = DamageRecord::select(
            'sector',
            DB::raw('count(*) as count'),
            DB::raw('sum(estimated_cost_usd) as total_usd'),
            DB::raw('sum(estimated_cost_ils) as total_ils')
        )->groupBy('sector')->get()->keyBy('sector');

        // Standard sectors map
        $sectors = [
            'real_estate' => [
                'name_ar' => 'المباني والوحدات العقارية',
                'color' => 'blue',
                'icon' => 'building-office-2',
            ],
            'infrastructure' => [
                'name_ar' => 'البنية التحتية والشبكات',
                'color' => 'amber',
                'icon' => 'bolt',
            ],
            'public_facilities' => [
                'name_ar' => 'المرافق العامة والحيوية',
                'color' => 'emerald',
                'icon' => 'academic-cap',
            ],
            'economic_crafts' => [
                'name_ar' => 'القطاع الاقتصادي والحرفي',
                'color' => 'purple',
                'icon' => 'briefcase',
            ],
            'municipal_assets' => [
                'name_ar' => 'ممتلكات وأصول البلدية',
                'color' => 'rose',
                'icon' => 'truck',
            ],
        ];

        // 3. Damage Levels Breakdown
        $damageLevels = DamageRecord::select(
            'damage_level',
            DB::raw('count(*) as count')
        )->groupBy('damage_level')->pluck('count', 'damage_level')->toArray();

        // 4. Zone Breakdown (Top affected areas)
        $zoneStats = DamageRecord::select(
            'zone_id',
            DB::raw('count(*) as count'),
            DB::raw('sum(estimated_cost_usd) as total_usd')
        )
        ->whereNotNull('zone_id')
        ->groupBy('zone_id')
        ->orderByDesc('count')
        ->limit(7)
        ->get();

        $zones = BhmZone::whereIn('id', $zoneStats->pluck('zone_id'))->pluck('zone_name', 'id');

        // 5. Recent Damage Records
        $recentRecords = DamageRecord::with(['zone', 'pricingCriteria'])
            ->latest('updated_at')
            ->limit(10)
            ->get();

        // 6. Criteria Count
        $criteriaCount = PricingCriteria::where('is_active', true)->count();
        $donorsCount = DonorOrganization::where('status', 'active')->count();

        return view('damage.dashboard', compact(
            'totalRecords',
            'totalCostUsd',
            'totalCostIls',
            'totalApproved',
            'totalReview',
            'totalDraft',
            'sectorStats',
            'sectors',
            'damageLevels',
            'zoneStats',
            'zones',
            'recentRecords',
            'criteriaCount',
            'donorsCount'
        ));
    }
}
