<?php

namespace App\Http\Controllers\Damage;

use App\Http\Controllers\Controller;
use App\Models\Damage\DamageRecord;
use App\Models\Damage\PricingCriteria;
use App\Models\Damage\DamageAuditLog;
use App\Models\Damage\DamageAttachment;
use App\Models\Buildings\BhmZone;
use App\Models\Buildings\BhmStreet;
use App\Services\Damage\DamageBaselineService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DamageRecordController extends Controller
{
    /**
     * Build filtered and sorted query for records.
     */
    protected function buildFilteredQuery(Request $request)
    {
        $query = DamageRecord::with(['zone', 'street', 'pricingCriteria']);

        // Filters
        if ($request->filled('sector')) {
            $query->where('sector', $request->sector);
        }

        if ($request->filled('damage_level')) {
            $query->where('damage_level', $request->damage_level);
        }

        if ($request->filled('workflow_status')) {
            $query->where('workflow_status', $request->workflow_status);
        }

        if ($request->filled('zone_id')) {
            $query->where('zone_id', $request->zone_id);
        }

        if ($request->filled('street_id')) {
            $query->where('street_id', $request->street_id);
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('record_number', 'LIKE', "%{$s}%")
                  ->orWhere('name', 'LIKE', "%{$s}%")
                  ->orWhere('citizen_name', 'LIKE', "%{$s}%")
                  ->orWhere('citizen_national_id', 'LIKE', "%{$s}%")
                  ->orWhere('parcel_number', 'LIKE', "%{$s}%")
                  ->orWhere('block_number', 'LIKE', "%{$s}%")
                  ->orWhere('building_number', 'LIKE', "%{$s}%");
            });
        }

        // Sorting
        $sortField = $request->get('sort', 'id');
        $sortDirection = strtolower($request->get('direction', 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowedSorts = [
            'id',
            'record_number',
            'name',
            'sector',
            'damage_level',
            'area_sqm',
            'estimated_cost_usd',
            'estimated_cost_ils',
            'workflow_status',
            'created_at',
        ];

        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->latest('id');
        }

        return $query;
    }

    /**
     * Display a listing of damage records with sector tabs and multi-criteria filters.
     */
    public function index(Request $request)
    {
        $query = $this->buildFilteredQuery($request);

        // Sector counts for tabs
        $sectorCounts = DamageRecord::select('sector', DB::raw('count(*) as count'))
            ->groupBy('sector')
            ->pluck('count', 'sector')
            ->toArray();

        $records = $query->paginate(20)->withQueryString();

        $zones = BhmZone::orderBy('zone_name')->pluck('zone_name', 'id');
        $streets = BhmStreet::orderBy('street_name')->limit(100)->pluck('street_name', 'id');

        return view('damage.records.index', compact('records', 'zones', 'streets', 'sectorCounts'));
    }

    /**
     * Export filtered damage records to Excel (CSV with UTF-8 BOM).
     */
    public function export(Request $request)
    {
        $sector = $request->query('sector');
        $sectorTitles = [
            'real_estate' => 'المباني_والعقارات',
            'infrastructure' => 'البنية_التحتية_والشبكات',
            'public_facilities' => 'المرافق_العامة_والحيوية',
            'economic_crafts' => 'القطاع_الاقتصادي_والحرفي',
            'municipal_assets' => 'أصول_البلدية',
        ];
        $sectorSuffix = $sector && isset($sectorTitles[$sector]) ? '_' . $sectorTitles[$sector] : '_كافة_القطاعات';
        $fileName = 'سجلات_حصر_الأضرار' . $sectorSuffix . '_' . date('Y-m-d') . '.csv';
        $asciiFilename = 'damage_records_' . ($sector ?: 'all') . '_' . date('Y-m-d') . '.csv';
        $encodedFilename = rawurlencode($fileName);

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$asciiFilename}\"; filename*=UTF-8''{$encodedFilename}",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $query = $this->buildFilteredQuery($request);

        $callback = function () use ($query) {
            $handle = fopen('php://output', 'w');
            
            // UTF-8 BOM for Microsoft Excel compatibility
            fputs($handle, "\xEF\xBB\xBF");

            // Header banner
            fputcsv($handle, ['بلدية بيت حانون - الدائرة الهندسية والتنظيم - منظومة حصر الأضرار وتقييم الاحتياجات']);
            fputcsv($handle, ['تقرير سجلات حصر الأضرار الميدانية المعروضة']);
            fputcsv($handle, ['تاريخ واستخراج التقرير: ' . date('Y-m-d H:i'), 'المستخدم: ' . (Auth::user()?->name ?? 'النظام')]);
            fputcsv($handle, []);

            // Column Headers
            fputcsv($handle, [
                '#',
                'رقم السجل',
                'القطاع',
                'اسم المنشأة / العقار أو الشارع',
                'مستوى الضرر',
                'الحالة التشغيلية',
                'المنطقة / الحي',
                'الشارع',
                'رقم الحوض',
                'رقم القطعة',
                'رقم المبنى',
                'الهيكل الإنشائي',
                'مساحة المسقط (م²)',
                'عدد الطوابق',
                'عدد الوحدات',
                'طول الطريق (م.ط)',
                'عرض الطريق (م)',
                'نوع الطريق',
                'اسم المالك / المواطن',
                'رقم الهوية',
                'رقم الهاتف',
                'معيار التسعير المعتمد',
                'التقدير المالي بالدولار ($)',
                'التقدير المالي بالشيكل (₪)',
                'حالة الاعتماد',
                'تاريخ الحصر الأولي',
                'الملاحظات الهندسية',
            ]);

            $totalUsd = 0;
            $totalIls = 0;
            $idx = 0;

            // Stream chunked records
            $query->chunk(500, function ($records) use ($handle, &$idx, &$totalUsd, &$totalIls) {
                foreach ($records as $r) {
                    $idx++;
                    $totalUsd += (float)$r->estimated_cost_usd;
                    $totalIls += (float)$r->estimated_cost_ils;

                    $sectorLabel = match ($r->sector) {
                        'real_estate' => 'المباني والعقارات',
                        'infrastructure' => 'البنية التحتية والشبكات',
                        'public_facilities' => 'المرافق العامة والحيوية',
                        'economic_crafts' => 'القطاع الاقتصادي والحرفي',
                        'municipal_assets' => 'أصول البلدية ومرافقها',
                        default => $r->sector,
                    };

                    $damageLevelLabel = match ($r->damage_level) {
                        'total_destruction' => 'مهدم كلياً (تدمير شامل)',
                        'severe_partial', 'severe_damage' => 'ضرر جزئي بليغ (غير صالح للسكن)',
                        'moderate_partial', 'partial_damage' => 'ضرر جزئي متوسط (قابل للتأهيل)',
                        'minor', 'minor_damage' => 'ضرر جزئي طفيف',
                        default => $r->damage_level,
                    };

                    $operationalLabel = match ($r->operational_status) {
                        'destroyed' => 'مدمر كلياً وخارج الخدمة',
                        'out_of_service' => 'خارج الخدمة مؤقتاً',
                        'partially_operational' => 'تشغيل جزئي ومحدود',
                        'operational' => 'صالح وقيد التشغيل',
                        default => $r->operational_status ?? '-',
                    };

                    $workflowLabel = match ($r->workflow_status) {
                        'final_approved' => 'معتمد نهائياً',
                        'engineering_review' => 'قيد المراجعة الهندسية',
                        'draft' => 'مسودة أولية',
                        default => $r->workflow_status,
                    };

                    $structureLabel = match ($r->structure_type) {
                        'reinforced_concrete' => 'باطون مسلح',
                        'asbestos' => 'أسبست / زنكو',
                        'steel_hanger' => 'هنغار معدني',
                        'stone' => 'حجر / قديم',
                        default => $r->structure_type ?? '-',
                    };

                    fputcsv($handle, [
                        $idx,
                        $r->record_number,
                        $sectorLabel,
                        $r->name,
                        $damageLevelLabel,
                        $operationalLabel,
                        $r->zone?->zone_name ?? '-',
                        $r->street?->street_name ?? ($r->street?->name ?? '-'),
                        $r->block_number ?? '-',
                        $r->parcel_number ?? '-',
                        $r->building_number ?? '-',
                        $structureLabel,
                        $r->area_sqm > 0 ? number_format($r->area_sqm, 2) : '-',
                        $r->floors_count ?? '-',
                        $r->units_count ?? '-',
                        $r->length_meters > 0 ? number_format($r->length_meters, 2) : '-',
                        $r->width_meters > 0 ? number_format($r->width_meters, 2) : '-',
                        $r->road_type ?? '-',
                        $r->citizen_name ?? '-',
                        $r->citizen_national_id ? "'" . $r->citizen_national_id : '-',
                        $r->citizen_phone ? "'" . $r->citizen_phone : '-',
                        $r->pricingCriteria?->name_ar ?? '-',
                        number_format($r->estimated_cost_usd, 2, '.', ''),
                        number_format($r->estimated_cost_ils, 2, '.', ''),
                        $workflowLabel,
                        $r->initial_assessment_date ? $r->initial_assessment_date->format('Y-m-d') : '-',
                        $r->notes ?? '-',
                    ]);
                }
            });

            // Summary Totals Row
            fputcsv($handle, []);
            fputcsv($handle, [
                'الإجمالي العام',
                $idx . ' سجل',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                'مجموع التكاليف التقديرية:',
                number_format($totalUsd, 2, '.', ''),
                number_format($totalIls, 2, '.', ''),
                '',
                '',
                '',
            ]);

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show form to create a new damage assessment.
     */
    public function create(Request $request)
    {
        $defaultSector = $request->query('sector', 'real_estate');
        $zones = BhmZone::orderBy('zone_name')->get();
        $streets = BhmStreet::orderBy('street_name')->get();
        $pricingCriteria = PricingCriteria::where('is_active', true)->get();

        return view('damage.records.create', compact('defaultSector', 'zones', 'streets', 'pricingCriteria'));
    }

    /**
     * Store newly created damage record.
     */
    public function store(Request $request, DamageBaselineService $baselineService)
    {
        $validated = $request->validate([
            'sector' => 'required|in:real_estate,infrastructure,public_facilities,economic_crafts,municipal_assets',
            'name' => 'required|string|max:255',
            'damage_level' => 'required|in:total_destruction,severe_partial,moderate_partial,minor',
            'operational_status' => 'nullable|in:destroyed,out_of_service,partially_operational,operational',
            'urgent_intervention_needed' => 'nullable|boolean',
            'zone_id' => 'nullable|exists:bhm_zones,id',
            'street_id' => 'nullable|exists:bhm_streets,id',
            'block_number' => 'nullable|string|max:50',
            'parcel_number' => 'nullable|string|max:50',
            'building_number' => 'nullable|string|max:50',
            'structure_type' => 'nullable|string|max:100',
            'area_sqm' => 'nullable|numeric|min:0',
            'floors_count' => 'nullable|integer|min:1',
            'units_count' => 'nullable|integer|min:0',
            'length_meters' => 'nullable|numeric|min:0',
            'width_meters' => 'nullable|numeric|min:0',
            'road_type' => 'nullable|string|max:50',
            'facility_type' => 'nullable|string|max:50',
            'operating_entity' => 'nullable|string|max:150',
            'pricing_criteria_id' => 'nullable|exists:dams_pricing_criteria,id',
            'citizen_name' => 'nullable|string|max:255',
            'citizen_national_id' => 'nullable|string|max:20',
            'citizen_phone' => 'nullable|string|max:30',
            'notes' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $year = date('Y');
        $seq = DamageRecord::count() + 1;
        $validated['record_number'] = 'DAM-' . $year . '-' . sprintf('%06d', $seq);
        $validated['workflow_status'] = 'draft';
        if (empty($validated['operational_status'])) {
            $validated['operational_status'] = $validated['damage_level'] === 'total_destruction' ? 'destroyed' : 'partially_operational';
        }
        $validated['certificate_uuid'] = (string) Str::uuid();
        $validated['certificate_qr_hash'] = hash('sha256', $validated['certificate_uuid'] . '-' . config('app.key'));
        $validated['initial_assessment_date'] = date('Y-m-d');
        $validated['audit_created_by'] = Auth::id();

        $sectorEntityMap = [
            'real_estate' => 'building',
            'infrastructure' => 'street',
            'public_facilities' => 'facility',
            'economic_crafts' => 'craft',
            'municipal_assets' => 'asset',
        ];
        $validated['entity_type'] = $sectorEntityMap[$validated['sector']] ?? 'building';
        $validated['length_meters'] = !empty($validated['length_meters']) ? (float)$validated['length_meters'] : 0.0;
        $validated['width_meters'] = !empty($validated['width_meters']) ? (float)$validated['width_meters'] : 0.0;
        $validated['area_sqm'] = !empty($validated['area_sqm']) ? (float)$validated['area_sqm'] : 0.0;
        $validated['floors_count'] = !empty($validated['floors_count']) ? (int)$validated['floors_count'] : 1;
        $validated['units_count'] = !empty($validated['units_count']) ? (int)$validated['units_count'] : 1;

        $record = DamageRecord::create($validated);

        // Recalculate cost
        $baselineService->recalculateCost($record);

        // Audit Log
        DamageAuditLog::create([
            'record_id' => $record->id,
            'user_id' => Auth::id(),
            'action' => 'created',
            'new_values' => $record->toArray(),
            'notes' => 'تم إنشاء السجل يدوياً في النظام',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('damage.records.show', $record->id)
            ->with('success', 'تم حفظ سجل حصر الأضرار بنجاح وتوليد رمز السجل ' . $record->record_number);
    }

    /**
     * Display a single damage record with all technical, financial, and comparison details.
     */
    public function show($id)
    {
        $record = DamageRecord::with([
            'zone',
            'subzone',
            'street',
            'pricingCriteria',
            'attachments',
            'auditLogs.user',
            'building.owners',
        ])->findOrFail($id);

        $availableCriteria = PricingCriteria::where('sector', $record->sector)
            ->where('is_active', true)
            ->get();

        return view('damage.records.show', compact('record', 'availableCriteria'));
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $record = DamageRecord::findOrFail($id);
        $zones = BhmZone::orderBy('zone_name')->get();
        $pricingCriteria = PricingCriteria::where('sector', $record->sector)->where('is_active', true)->get();

        return view('damage.records.edit', compact('record', 'zones', 'pricingCriteria'));
    }

    /**
     * Update damage record details and recalculate costs.
     */
    public function update(Request $request, $id, DamageBaselineService $baselineService)
    {
        $record = DamageRecord::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'damage_level' => 'required|in:total_destruction,severe_partial,moderate_partial,minor',
            'operational_status' => 'required|in:operational,partially_operational,out_of_service,destroyed',
            'zone_id' => 'nullable|exists:bhm_zones,id',
            'street_id' => 'nullable|exists:bhm_streets,id',
            'block_number' => 'nullable|string|max:50',
            'parcel_number' => 'nullable|string|max:50',
            'area_sqm' => 'nullable|numeric|min:0',
            'floors_count' => 'nullable|integer|min:1',
            'units_count' => 'nullable|integer|min:0',
            'length_meters' => 'nullable|numeric|min:0',
            'width_meters' => 'nullable|numeric|min:0',
            'road_type' => 'nullable|string|max:50',
            'structure_type' => 'nullable|string|max:100',
            'pricing_criteria_id' => 'nullable|exists:dams_pricing_criteria,id',
            'citizen_name' => 'nullable|string|max:255',
            'citizen_national_id' => 'nullable|string|max:20',
            'citizen_phone' => 'nullable|string|max:30',
            'notes' => 'nullable|string',
            'damage_description' => 'nullable|string',
            'urgent_intervention_needed' => 'nullable|boolean',
        ]);

        $oldData = $record->toArray();

        if (array_key_exists('length_meters', $validated)) {
            $validated['length_meters'] = !empty($validated['length_meters']) ? (float)$validated['length_meters'] : 0.0;
        }
        if (array_key_exists('width_meters', $validated)) {
            $validated['width_meters'] = !empty($validated['width_meters']) ? (float)$validated['width_meters'] : 0.0;
        }
        if (array_key_exists('area_sqm', $validated)) {
            $validated['area_sqm'] = !empty($validated['area_sqm']) ? (float)$validated['area_sqm'] : 0.0;
        }
        if (array_key_exists('floors_count', $validated)) {
            $validated['floors_count'] = !empty($validated['floors_count']) ? (int)$validated['floors_count'] : 1;
        }
        if (array_key_exists('units_count', $validated)) {
            $validated['units_count'] = !empty($validated['units_count']) ? (int)$validated['units_count'] : 1;
        }

        $record->update($validated);

        // Recalculate
        $baselineService->recalculateCost($record);

        // Audit Log
        DamageAuditLog::create([
            'record_id' => $record->id,
            'user_id' => Auth::id(),
            'action' => 'updated',
            'old_values' => $oldData,
            'new_values' => $record->fresh()->toArray(),
            'notes' => 'تم تعديل بيانات السجل وإعادة احتساب التقدير المالي',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('damage.records.show', $record->id)
            ->with('success', 'تم تحديث سجل حصر الأضرار بنجاح.');
    }

    /**
     * Transition workflow status (draft -> engineering_review -> final_approved).
     */
    public function updateWorkflow(Request $request, $id)
    {
        $record = DamageRecord::findOrFail($id);

        $validated = $request->validate([
            'workflow_status' => 'required|in:draft,engineering_review,final_approved',
            'action_notes' => 'nullable|string|max:500',
        ]);

        $oldStatus = $record->workflow_status;
        $newStatus = $validated['workflow_status'];

        $updateData = ['workflow_status' => $newStatus];

        if ($newStatus === 'final_approved') {
            $updateData['approved_by'] = Auth::id();
            $updateData['approved_at'] = now();
        }

        $record->update($updateData);

        // Audit Log
        DamageAuditLog::create([
            'record_id' => $record->id,
            'user_id' => Auth::id(),
            'action' => 'workflow_' . $newStatus,
            'old_values' => ['workflow_status' => $oldStatus],
            'new_values' => ['workflow_status' => $newStatus],
            'notes' => $validated['action_notes'] ?: "تغيير حالة السجل من {$oldStatus} إلى {$newStatus}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('damage.records.show', $record->id)
            ->with('success', 'تم تحديث حالة دورة الاعتماد للسجل بنجاح.');
    }

    /**
     * Smart deduplication check endpoint (AJAX)
     */
    public function checkDuplicate(Request $request)
    {
        $block = $request->query('block_number');
        $parcel = $request->query('parcel_number');
        $idCard = $request->query('citizen_national_id');
        $currentId = $request->query('current_id');

        $query = DamageRecord::query();
        if ($currentId) {
            $query->where('id', '!=', $currentId);
        }

        $duplicates = [];

        if ($block && $parcel) {
            $match = (clone $query)->where('block_number', $block)->where('parcel_number', $parcel)->first();
            if ($match) {
                $duplicates[] = [
                    'type' => 'block_parcel',
                    'message' => "يوجد سجل مسجل مسبقاً بنفس رقم الحوض والقطعة (#{$match->record_number} - {$match->name})",
                    'record_id' => $match->id,
                ];
            }
        }

        if ($idCard) {
            $match = (clone $query)->where('citizen_national_id', $idCard)->first();
            if ($match) {
                $duplicates[] = [
                    'type' => 'national_id',
                    'message' => "يوجد سجل مرتبط برقم الهوية ({$idCard}) مسبقاً (#{$match->record_number} - {$match->name})",
                    'record_id' => $match->id,
                ];
            }
        }

        return response()->json([
            'has_duplicate' => count($duplicates) > 0,
            'duplicates' => $duplicates,
        ]);
    }

    /**
     * Recalculate cost for a single record.
     */
    public function recalculate($id, DamageBaselineService $baselineService)
    {
        $record = DamageRecord::findOrFail($id);
        $baselineService->recalculateCost($record);

        return redirect()->route('damage.records.show', $record->id)
            ->with('success', 'تمت إعادة احتساب التقدير المالي للكميات بنجاح وفق المعايير الهندسية الحالية.');
    }
}
