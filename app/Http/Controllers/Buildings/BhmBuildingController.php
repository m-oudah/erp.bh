<?php

namespace App\Http\Controllers\Buildings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buildings\BhmBuilding;
use App\Models\Buildings\BhmZone;
use App\Models\Buildings\BhmSubzone;
use App\Models\Buildings\BhmBuildingType;
use App\Models\Buildings\BhmBuildingStatus;
use App\Models\Buildings\BhmBuildingPropertyType;
use App\Models\Buildings\BhmStreet;
use App\Models\Buildings\BhmSupervisor;
use App\Models\Buildings\BhmBuildingMaterial;
use App\Models\Buildings\BhmBuildingUse;
use App\Models\Buildings\BhmAttachment;
use App\Models\Buildings\BhmBuildFinancial;
use App\Models\ArchiveFile;
use Illuminate\Support\Facades\Storage;

class BhmBuildingController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only([
            'file_number', 'building_number', 'block_number', 'parcel_number',
            'building_name', 'zone_id', 'street_id', 'street_number', 'building_type_id',
            'id_card', 'owner_name'
        ]);

        $buildings = BhmBuilding::with(['zone', 'street', 'buildingType', 'owners'])
            ->filter($filters)
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        $zones = BhmZone::orderBy('id')->get();
        $buildingTypes = BhmBuildingType::orderBy('name')->get();

        // Load all streets sorted with named streets first then by number
        $streets = BhmStreet::orderByRaw("CASE WHEN street_name IS NOT NULL AND street_name != '' THEN 0 ELSE 1 END, street_name ASC, street_number ASC")->get();

        return view('buildings.buildings.index', compact('buildings', 'filters', 'zones', 'buildingTypes', 'streets'));
    }

    public function show($id)
    {
        $building = BhmBuilding::with([
            'zone', 'street', 'subzone', 'buildingType', 'buildingStatus', 'buildingPropertyType',
            'owners', 'floors', 'units', 'financial', 'supervisor', 'uses', 'materials'
        ])->findOrFail($id);

        $archiveFiles = collect();
        if ($building->file_number && trim($building->file_number) !== '0') {
            $raw = trim($building->file_number);
            $normalized = str_replace(['أ', 'إ', 'آ'], 'ا', $raw);
            $archiveFiles = ArchiveFile::with('fileType')
                ->where('file_no', $raw)
                ->orWhere('file_no', $normalized)
                ->orWhereRaw("REPLACE(REPLACE(REPLACE(file_no, 'أ', 'ا'), 'إ', 'ا'), 'آ', 'ا') = ?", [$normalized])
                ->get();
        }

        return view('buildings.buildings.show', compact('building', 'archiveFiles'));
    }

    public function searchArchiveFiles(Request $request)
    {
        $q = trim($request->input('q', ''));
        $typeId = $request->input('type_id');

        $query = ArchiveFile::with('fileType')->withCount('documents');

        if ($typeId) {
            $query->where('archive_file_type_id', $typeId);
        }

        if (mb_strlen($q) >= 1) {
            $normalized = str_replace(['أ', 'إ', 'آ'], 'ا', $q);
            $cleanedTerm = preg_replace('/[+\-><\(\)~*\"@]+/', ' ', $q);
            $terms = array_filter(explode(' ', trim($cleanedTerm)));

            $query->where(function ($sub) use ($q, $normalized, $terms) {
                $sub->where('file_no', $q)
                    ->orWhere('file_no', 'like', "{$q}%")
                    ->orWhere('file_no', 'like', "%{$q}%")
                    ->orWhereRaw("REPLACE(REPLACE(REPLACE(file_no, 'أ', 'ا'), 'إ', 'ا'), 'آ', 'ا') LIKE ?", ["%{$normalized}%"]);

                if (!empty($terms)) {
                    $booleanQuery = implode(' ', array_map(fn($t) => '+' . $t . '*', $terms));
                    $sub->orWhereRaw("MATCH(file_name) AGAINST(? IN BOOLEAN MODE)", [$booleanQuery]);
                }

                $sub->orWhere('file_name', 'like', "%{$q}%")
                    ->orWhereRaw("REPLACE(REPLACE(REPLACE(file_name, 'أ', 'ا'), 'إ', 'ا'), 'آ', 'ا') LIKE ?", ["%{$normalized}%"]);
            });
        }

        $files = $query->latest()->take(25)->get();

        return response()->json([
            'status' => 'success',
            'data' => $files->map(fn($f) => [
                'id' => $f->id,
                'file_no' => $f->file_no,
                'file_name' => $f->file_name,
                'type_id' => $f->archive_file_type_id,
                'file_type' => $f->fileType?->name ?? 'غير محدد',
                'documents_count' => (int) $f->documents_count,
            ]),
        ]);
    }

    public function linkArchiveFile(Request $request, $id)
    {
        $request->validate([
            'file_no' => 'required|string|max:100',
        ]);

        $building = BhmBuilding::findOrFail($id);
        $fileNo = trim($request->input('file_no'));

        $archiveFile = ArchiveFile::with('fileType')->withCount('documents')
            ->where('file_no', $fileNo)
            ->orWhereRaw("REPLACE(REPLACE(REPLACE(file_no, 'أ', 'ا'), 'إ', 'ا'), 'آ', 'ا') = ?", [
                str_replace(['أ', 'إ', 'آ'], 'ا', $fileNo)
            ])
            ->first();

        if (!$archiveFile) {
            return response()->json([
                'status' => 'error',
                'message' => 'لم يتم العثور على ملف الأرشيف المحدد في النظام.',
            ], 404);
        }

        $building->file_number = $archiveFile->file_no;
        $building->save();

        return response()->json([
            'status' => 'success',
            'message' => 'تم ربط المبنى بملف الأرشيف بنجاح.',
            'file_number' => $building->file_number,
            'archive_file' => [
                'id' => $archiveFile->id,
                'file_no' => $archiveFile->file_no,
                'file_name' => $archiveFile->file_name,
                'file_type' => $archiveFile->fileType?->name ?? 'غير محدد',
                'documents_count' => (int) $archiveFile->documents_count,
            ],
        ]);
    }

    public function unlinkArchiveFile($id)
    {
        if (!auth()->user()->can('Update-Building') && !auth()->user()->can('bhm.buildings.edit') && !auth()->user()->can('Read-Buildings')) {
            abort(403, 'غير مصرح لك بتعديل بيانات المبنى.');
        }

        $building = BhmBuilding::findOrFail($id);
        $building->file_number = null;
        $building->save();

        return response()->json([
            'status' => 'success',
            'message' => 'تمت إزالة ربط المبنى بملف الأرشيف بنجاح.',
        ]);
    }

    public function edit($id)
    {
        if (!auth()->user()->can('Update-Building') && !auth()->user()->can('bhm.buildings.edit')) {
            abort(403, 'غير مصرح لك بتعديل بيانات المبنى.');
        }

        $building = BhmBuilding::with([
            'zone', 'street', 'subzone', 'buildingType', 'buildingStatus', 'buildingPropertyType',
            'owners.units', 'floors', 'units.floor', 'units.buildingOwner', 'financial', 'supervisor', 'uses', 'materials', 'attachments'
        ])->findOrFail($id);

        $targetArchiveFile = null;
        if ($building->file_number && trim($building->file_number) !== '0') {
            $raw = trim($building->file_number);
            $normalized = str_replace(['أ', 'إ', 'آ'], 'ا', $raw);
            $targetArchiveFile = ArchiveFile::with('fileType')->withCount('documents')
                ->where('file_no', $raw)
                ->orWhere('file_no', $normalized)
                ->orWhereRaw("REPLACE(REPLACE(REPLACE(file_no, 'أ', 'ا'), 'إ', 'ا'), 'آ', 'ا') = ?", [$normalized])
                ->first();
        }

        $archiveFileData = null;
        if ($targetArchiveFile) {
            $archiveFileData = [
                'id' => $targetArchiveFile->id,
                'file_no' => $targetArchiveFile->file_no,
                'file_name' => $targetArchiveFile->file_name,
                'file_type' => $targetArchiveFile->fileType?->name ?? 'غير محدد',
                'documents_count' => (int) $targetArchiveFile->documents_count,
            ];
        } elseif (filled($building->file_number) && trim($building->file_number) !== '0') {
            $archiveFileData = [
                'id' => null,
                'file_no' => $building->file_number,
                'file_name' => 'ملف رقم ' . $building->file_number,
                'file_type' => 'غير مسجل بالأرشيف',
                'documents_count' => 0,
            ];
        }

        $zones = BhmZone::orderBy('id')->get();
        $subzones = BhmSubzone::orderBy('id')->get();
        $streets = BhmStreet::orderByRaw("CASE WHEN street_name IS NOT NULL AND street_name != '' THEN 0 ELSE 1 END, street_name ASC, street_number ASC")->get();
        $buildingTypes = BhmBuildingType::orderBy('name')->get();
        $buildingStatuses = BhmBuildingStatus::orderBy('id')->get();
        $buildingPropertyTypes = BhmBuildingPropertyType::orderBy('id')->get();
        $supervisors = BhmSupervisor::where('is_active', true)->orderBy('name')->get();
        if ($supervisors->isEmpty()) {
            $supervisors = BhmSupervisor::orderBy('name')->get();
        }
        $buildingMaterials = BhmBuildingMaterial::orderBy('id')->get();
        $buildingUses = BhmBuildingUse::orderBy('id')->get();

        return view('buildings.buildings.edit', compact(
            'building', 'zones', 'subzones', 'streets', 'buildingTypes',
            'buildingStatuses', 'buildingPropertyTypes', 'supervisors',
            'buildingMaterials', 'buildingUses', 'archiveFileData'
        ));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('Update-Building') && !auth()->user()->can('bhm.buildings.edit')) {
            abort(403, 'غير مصرح لك بتعديل بيانات المبنى.');
        }

        $building = BhmBuilding::findOrFail($id);

        $request->validate([
            'building_number' => 'nullable|string|max:50',
            'file_number' => 'nullable|string|max:50',
            'building_name' => 'nullable|string|max:500',
            'zone_id' => 'nullable|exists:bhm_zones,id',
            'subzone_id' => 'nullable|exists:bhm_subzones,id',
            'street_id' => 'nullable|exists:bhm_streets,id',
            'block_number' => 'nullable|string|max:20',
            'parcel_number' => 'nullable|string|max:20',
            'building_type_id' => 'nullable|exists:bhm_building_types,id',
            'building_status_id' => 'nullable|exists:bhm_building_statuses,id',
            'building_property_type_id' => 'nullable|exists:bhm_building_property_types,id',
            'supervisor_id' => 'nullable|exists:bhm_supervisors,id',
            'building_date' => 'nullable|date',
            'out_status' => 'nullable|integer',
            'overall_status' => 'nullable|integer',
            'ownership_notes' => 'nullable|string|max:1000',
            'overall_status_notes' => 'nullable|string|max:1000',
            'building_usage_notes' => 'nullable|string|max:1000',
            'wall_notes' => 'nullable|string|max:500',
            'last_floor_notes' => 'nullable|string|max:500',
            'finish_notes' => 'nullable|string|max:500',
            'water_source' => 'nullable|integer',
            'sewage' => 'nullable|integer',
            'electricity_source' => 'nullable|integer',
            'elevators_count' => 'nullable|integer|min:0',
            'escape_stairs' => 'nullable|integer|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:10240',
            'materials' => 'nullable|array',
            'materials.*' => 'exists:bhm_building_materials,id',
            'uses' => 'nullable|array',
            'uses.*' => 'exists:bhm_building_uses,id',
            'land_area' => 'nullable|numeric|min:0',
            'dev_fees' => 'nullable|numeric|min:0',
            'dev_disc_val' => 'nullable|numeric|min:0',
            'dev_disc_rat' => 'nullable|numeric|min:0',
            'dev_per_meter' => 'nullable|numeric|min:0',
            'fees_remains' => 'nullable|numeric|min:0',
            'financial_notes' => 'nullable|string|max:1000',
        ]);

        $buildingData = $request->only([
            'building_number', 'file_number', 'building_name', 'zone_id', 'subzone_id', 'street_id',
            'block_number', 'parcel_number', 'building_type_id', 'building_status_id', 'building_property_type_id',
            'supervisor_id', 'building_date', 'out_status', 'overall_status', 'ownership_notes', 'overall_status_notes',
            'building_usage_notes', 'wall_notes', 'last_floor_notes', 'finish_notes', 'water_source', 'sewage',
            'electricity_source', 'elevators_count', 'escape_stairs'
        ]);

        $booleanFields = [
            'historical', 'adding_new_floor', 'abandond', 'building_special_case',
            'finish_qesara', 'finish_italian', 'finish_tiles', 'finish_j_stone', 'finish_granuleet', 'finish_other',
            'wall_stone', 'wall_stone_concrete', 'wall_reinforced_concrete', 'wall_concrete_stone', 'wall_sand_stone', 'wall_other',
            'last_floor_concrete', 'last_floor_asbast', 'last_floor_carmeed', 'last_floor_zenko', 'last_floor_other',
        ];

        foreach ($booleanFields as $field) {
            $buildingData[$field] = $request->boolean($field);
        }

        // Handle Photo Upload or Removal
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $path = $file->store('buildings', 'public');

            $attachment = $building->attachments()->first();
            if ($attachment && $attachment->path && Storage::disk('public')->exists($attachment->path)) {
                Storage::disk('public')->delete($attachment->path);
            }

            if (!$attachment) {
                $attachment = new BhmAttachment();
                $attachment->building_id = $building->id;
            }

            $attachment->path = $path;
            $attachment->file_name = $file->getClientOriginalName();
            $attachment->file_type = $file->getClientMimeType();
            $attachment->file_size = $file->getSize();
            $attachment->created_by = auth()->id();
            $attachment->save();

            $buildingData['image'] = $path;
        } elseif ($request->boolean('remove_photo')) {
            $attachment = $building->attachments()->first();
            if ($attachment) {
                if ($attachment->path && Storage::disk('public')->exists($attachment->path)) {
                    Storage::disk('public')->delete($attachment->path);
                }
                $attachment->delete();
            }
            $buildingData['image'] = null;
        }

        $building->update($buildingData);

        // Sync Materials & Uses
        if ($request->has('materials')) {
            $building->materials()->sync($request->input('materials', []));
        } else {
            $building->materials()->detach();
        }

        if ($request->has('uses')) {
            $building->uses()->sync($request->input('uses', []));
        } else {
            $building->uses()->detach();
        }

        // Financial Data
        $hasFinancialInput = $request->filled('land_area') || $request->filled('dev_fees') ||
            $request->filled('dev_disc_val') || $request->filled('dev_disc_rat') ||
            $request->filled('dev_per_meter') || $request->filled('fees_remains') ||
            $request->filled('financial_notes');

        if ($hasFinancialInput) {
            $financialData = [
                'land_area' => $request->input('land_area'),
                'dev_fees' => $request->input('dev_fees'),
                'dev_disc_val' => $request->input('dev_disc_val'),
                'dev_disc_rat' => $request->input('dev_disc_rat'),
                'dev_per_meter' => $request->input('dev_per_meter'),
                'fees_remains' => $request->input('fees_remains'),
                'notes' => $request->input('financial_notes'),
                'updated_by' => auth()->user()->name ?? 'System',
            ];

            if ($building->financial) {
                $building->financial->update($financialData);
            } else {
                $financialData['building_id'] = $building->id;
                $financialData['build_id'] = $building->id;
                BhmBuildFinancial::create($financialData);
            }
        }

        return redirect()->route('buildings.buildings.show', $building->id)
            ->with('success', 'تم حفظ وتحديث بيانات المبنى بنجاح.');
    }
}
