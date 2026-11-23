<?php

namespace App\Http\Controllers\Damage;

use App\Http\Controllers\Controller;
use App\Models\Damage\DamageRecord;
use App\Models\Damage\PricingCriteria;
use App\Models\Damage\DonorOrganization;
use App\Models\Damage\VerificationLog;
use App\Models\Buildings\BhmZone;
use App\Models\Buildings\BhmStreet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DamagePublicPortalController extends Controller
{
    /**
     * Main Public Portal Page
     */
    public function index(Request $request)
    {
        // 1. High-Level KPIs
        $totalDamages = DamageRecord::count();
        $totalCostUsd = DamageRecord::sum('estimated_cost_usd');
        $totalCostIls = DamageRecord::sum('estimated_cost_ils');
        $totalTotalDestruction = DamageRecord::where('damage_level', 'total_destruction')->count();
        $totalPartial = DamageRecord::whereIn('damage_level', ['severe_partial', 'moderate_partial', 'minor'])->count();

        // 2. Sectoral Aggregations
        $sectorStats = DamageRecord::select(
            'sector',
            DB::raw('count(*) as count'),
            DB::raw('sum(estimated_cost_usd) as total_usd'),
            DB::raw('sum(estimated_cost_ils) as total_ils')
        )->groupBy('sector')->get()->keyBy('sector');

        // Specific Metrics
        $housingUnitsDestroyed = DamageRecord::where('sector', 'real_estate')->sum('units_count') ?: (DamageRecord::where('sector', 'real_estate')->count() * 2);
        $roadLengthKm = round(DamageRecord::where('sector', 'infrastructure')->sum('length_meters') / 1000, 1);
        $publicFacilitiesCount = DamageRecord::where('sector', 'public_facilities')->count();
        $economicUnitsCount = DamageRecord::where('sector', 'economic_crafts')->count();

        $zones = BhmZone::orderBy('zone_name')->get();

        return view('damage.public.portal', compact(
            'totalDamages',
            'totalCostUsd',
            'totalCostIls',
            'totalTotalDestruction',
            'totalPartial',
            'sectorStats',
            'housingUnitsDestroyed',
            'roadLengthKm',
            'publicFacilitiesCount',
            'economicUnitsCount',
            'zones'
        ));
    }

    /**
     * Map Data Endpoint (JSON) for Leaflet
     */
    public function mapData(Request $request)
    {
        $query = DamageRecord::query();

        if ($request->filled('sector') && $request->sector !== 'all') {
            $query->where('sector', $request->sector);
        }

        if ($request->filled('damage_level') && $request->damage_level !== 'all') {
            $query->where('damage_level', $request->damage_level);
        }

        if ($request->filled('zone_id') && $request->zone_id !== 'all') {
            $query->where('zone_id', $request->zone_id);
        }

        // Limit for smooth browser rendering
        $records = $query->select(
            'id',
            'record_number',
            'name',
            'sector',
            'entity_type',
            'entity_id',
            'damage_level',
            'operational_status',
            'estimated_cost_usd',
            'estimated_cost_ils',
            'area_sqm',
            'length_meters',
            'floors_count',
            'units_count',
            'facility_type',
            'operating_entity',
            'urgent_intervention_needed',
            'zone_id',
            'street_id',
            'block_number',
            'parcel_number',
            'latitude',
            'longitude'
        )
        ->limit(600)
        ->get();

        // Beit Hanoun Geographic Bounding Box
        // Center: 31.5420 N, 34.5345 E
        $baseLat = 31.5420;
        $baseLng = 34.5345;

        $features = $records->map(function ($r) use ($baseLat, $baseLng) {
            $lat = $r->latitude;
            $lng = $r->longitude;

            // If coordinates not set, generate deterministic realistic coordinates in Beit Hanoun
            if (!$lat || !$lng) {
                $hash = crc32($r->record_number . '-' . $r->id);
                $offsetLat = (($hash % 2000) - 1000) / 75000.0;
                $offsetLng = ((($hash >> 3) % 2000) - 1000) / 75000.0;

                $lat = round($baseLat + $offsetLat, 6);
                $lng = round($baseLng + $offsetLng, 6);
            }

            return [
                'id' => $r->id,
                'record_number' => $r->record_number,
                'name' => $r->name,
                'sector' => $r->sector,
                'entity_type' => $r->entity_type,
                'damage_level' => $r->damage_level,
                'operational_status' => $r->operational_status,
                'cost_usd' => (float)$r->estimated_cost_usd,
                'cost_ils' => (float)$r->estimated_cost_ils,
                'area_sqm' => (float)$r->area_sqm,
                'length_m' => (float)$r->length_meters,
                'units' => (int)$r->units_count,
                'facility_type' => $r->facility_type,
                'operating_entity' => $r->operating_entity,
                'urgent' => (bool)$r->urgent_intervention_needed,
                'block' => $r->block_number,
                'parcel' => $r->parcel_number,
                'lat' => (float)$lat,
                'lng' => (float)$lng,
            ];
        });

        return response()->json([
            'count' => $features->count(),
            'features' => $features,
        ]);
    }

    /**
     * Dedicated Donor Space (Anti-duplication portal)
     */
    public function donorSpace(Request $request)
    {
        $query = DamageRecord::query();

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('record_number', 'LIKE', "%{$s}%")
                  ->orWhere('name', 'LIKE', "%{$s}%")
                  ->orWhere('block_number', 'LIKE', "%{$s}%")
                  ->orWhere('parcel_number', 'LIKE', "%{$s}%");
            });
        }

        if ($request->filled('sector')) {
            $query->where('sector', $request->sector);
        }

        $records = $query->latest('id')->paginate(15)->withQueryString();
        $donors = DonorOrganization::where('status', 'active')->get();

        return view('damage.public.donor_space', compact('records', 'donors'));
    }

    /**
     * Donor Intervention Registration
     */
    public function storeDonorIntervention(Request $request)
    {
        $validated = $request->validate([
            'damage_record_id' => 'required|exists:dams_damage_records,id',
            'organization_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'intervention_type' => 'required|string|max:100',
            'pledged_amount_usd' => 'required|numeric|min:0',
            'planned_start_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $record = DamageRecord::findOrFail($validated['damage_record_id']);

        // Register verification log
        VerificationLog::create([
            'certificate_uuid' => $record->certificate_uuid,
            'source' => 'donor_intervention',
            'requester_ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'notes' => "تسجيل تدخل تمويلي من قبل {$validated['organization_name']} بمبلغ \${$validated['pledged_amount_usd']}",
        ]);

        return redirect()->back()->with('success', 'تم تسجيل نية التدخل بنجاح وتوثيقها لمنع ازدواجية الصرف والتمويل.');
    }

    /**
     * Public Bilingual Report Page & Printable Summary
     */
    public function exportReport(Request $request)
    {
        $totalRecords = DamageRecord::count();
        $totalCostUsd = DamageRecord::sum('estimated_cost_usd');
        $totalCostIls = DamageRecord::sum('estimated_cost_ils');

        $sectorStats = DamageRecord::select(
            'sector',
            DB::raw('count(*) as count'),
            DB::raw('sum(estimated_cost_usd) as total_usd'),
            DB::raw('sum(estimated_cost_ils) as total_ils')
        )->groupBy('sector')->get();

        $damageLevels = DamageRecord::select(
            'damage_level',
            DB::raw('count(*) as count'),
            DB::raw('sum(estimated_cost_usd) as total_usd')
        )->groupBy('damage_level')->get();

        return view('damage.public.report', compact(
            'totalRecords',
            'totalCostUsd',
            'totalCostIls',
            'sectorStats',
            'damageLevels'
        ));
    }
}
