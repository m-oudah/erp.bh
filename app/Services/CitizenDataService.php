<?php

namespace App\Services;

use App\Models\Buildings\BhmBuilding;
use App\Models\Buildings\BhmBuildingOwner;
use App\Models\Buildings\BhmCustomer;
use App\Models\Buildings\BhmEconomical;
use App\Models\Buildings\BhmEconomicalOwner;
use App\Models\Buildings\BhmUnit;
use Illuminate\Support\Collection;

class CitizenDataService
{
    /**
     * Find a citizen by ID card and verify mobile match.
     * Searches across Customers, Building Owners, and Economical Owners.
     */
    public function findAndMatchCitizen(string $idCard, string $inputMobile): ?array
    {
        $idCard = trim($idCard);
        $normInputMobile = SmsGatewayService::normalizeMobile($inputMobile);

        // 1. Check in bhm_customers
        $customer = BhmCustomer::where('id_no', $idCard)->first();
        
        // 2. Check in bhm_building_owners
        $owners = BhmBuildingOwner::where('id_card', $idCard)->get();
        
        // 3. Check in bhm_economical_owners
        $ecoOwners = BhmEconomicalOwner::where('id_card', $idCard)->get();

        // 4. Check in bhm_economical direct
        $economicals = BhmEconomical::where('id_card', $idCard)->get();

        $hasAnyRecord = $customer || $owners->isNotEmpty() || $ecoOwners->isNotEmpty() || $economicals->isNotEmpty();

        if (!$hasAnyRecord) {
            return null;
        }

        // Collect all registered phone numbers for this citizen
        $phones = collect();
        if ($customer && !empty($customer->mobile)) {
            $phones->push($customer->mobile);
        }
        if ($customer && !empty($customer->telephone)) {
            $phones->push($customer->telephone);
        }
        foreach ($owners as $owner) {
            if (!empty($owner->phone_number)) {
                $phones->push($owner->phone_number);
            }
        }
        foreach ($ecoOwners as $ecoOwner) {
            if (!empty($ecoOwner->phone)) {
                $phones->push($ecoOwner->phone);
            }
            if (!empty($ecoOwner->phone_number)) {
                $phones->push($ecoOwner->phone_number);
            }
        }
        foreach ($economicals as $eco) {
            if (!empty($eco->job_mobile)) {
                $phones->push($eco->job_mobile);
            }
            if (!empty($eco->job_phone)) {
                $phones->push($eco->job_phone);
            }
        }

        // Normalize all database phones
        $normalizedPhones = $phones->filter()->map(fn($p) => SmsGatewayService::normalizeMobile($p))->unique();

        // Check matching
        $isMatched = false;
        foreach ($normalizedPhones as $dbPhone) {
            if ($dbPhone === $normInputMobile || 
                (strlen($normInputMobile) >= 7 && str_ends_with($dbPhone, substr($normInputMobile, -7)))) {
                $isMatched = true;
                break;
            }
        }

        // In local development or if no phone number was found on file, allow matching with input phone
        if (app()->environment('local', 'testing') && !$isMatched && $normalizedPhones->isEmpty()) {
            $isMatched = true;
        }

        if (!$isMatched) {
            return [
                'exists' => true,
                'phone_matched' => false,
                'name' => $this->resolveCitizenName($customer, $owners, $ecoOwners, $economicals),
                'registered_phones_count' => $normalizedPhones->count(),
            ];
        }

        return [
            'exists' => true,
            'phone_matched' => true,
            'id_card' => $idCard,
            'mobile' => $normInputMobile,
            'name' => $this->resolveCitizenName($customer, $owners, $ecoOwners, $economicals),
        ];
    }

    /**
     * Resolve best citizen name from available records.
     */
    protected function resolveCitizenName($customer, $owners, $ecoOwners, $economicals): string
    {
        if ($customer && !empty($customer->name)) {
            return trim($customer->name);
        }

        foreach ($owners as $owner) {
            $name = $owner->full_name;
            if (!empty($name)) {
                return $name;
            }
        }

        foreach ($ecoOwners as $ecoOwner) {
            $name = trim(collect([$ecoOwner->first_name, $ecoOwner->second_name, $ecoOwner->third_name, $ecoOwner->sur_name])->filter()->implode(' '));
            if (!empty($name)) {
                return $name;
            }
        }

        foreach ($economicals as $eco) {
            if (!empty($eco->name)) {
                return trim($eco->name);
            }
        }

        return 'مواطن / مكلف';
    }

    /**
     * Find a citizen name by National ID from any municipal source.
     */
    public function findCitizenByIdCard(string $idCard): ?array
    {
        $idCard = trim($idCard);

        $customer = BhmCustomer::where('id_no', $idCard)->first();
        $owners = BhmBuildingOwner::where('id_card', $idCard)->get();
        $ecoOwners = BhmEconomicalOwner::where('id_card', $idCard)->get();
        $economicals = BhmEconomical::where('id_card', $idCard)->get();

        $hasAny = $customer || $owners->isNotEmpty() || $ecoOwners->isNotEmpty() || $economicals->isNotEmpty();
        if (!$hasAny) {
            return null;
        }

        return [
            'exists' => true,
            'id_card' => $idCard,
            'name' => $this->resolveCitizenName($customer, $owners, $ecoOwners, $economicals),
        ];
    }

    /**
     * Get all buildings owned by or associated with this citizen.
     */
    public function getCitizenBuildings(string $idCard): Collection
    {
        $ownerRecords = BhmBuildingOwner::where('id_card', $idCard)
            ->with([
                'building.street',
                'building.zone',
                'building.subzone',
                'building.units',
                'building.floors',
                'building.attachments',
            ])
            ->get();

        return $ownerRecords->map(function ($owner) {
            $b = $owner->building;
            if (!$b) {
                return null;
            }

            $photo = $b->attachments->first(function ($att) {
                $ext = strtolower($att->extension ?? pathinfo($att->path ?? $att->url ?? '', PATHINFO_EXTENSION));
                $mime = strtolower($att->file_type ?? '');
                return in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']) || str_starts_with($mime, 'image/');
            });

            $photoPath = $b->image ?? $photo?->path ?? $photo?->url;
            $photoUrl = null;
            if ($photoPath) {
                $ext = strtolower(pathinfo(parse_url($photoPath, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']) || empty($ext)) {
                    if (str_starts_with($photoPath, 'http://') || str_starts_with($photoPath, 'https://')) {
                        $photoUrl = $photoPath;
                    } else {
                        $photoUrl = asset('storage/' . ltrim($photoPath, '/'));
                    }
                }
            }

            return [
                'owner_id' => $owner->id,
                'ownership_share' => $owner->mokalaf,
                'is_approved' => (bool)$owner->is_approve,
                'building_id' => $b->id,
                'building_name' => $b->building_name,
                'building_number' => $b->building_number,
                'photo_url' => $photoUrl,
                'street_name' => $b->street?->name ?? 'غير محدد',
                'zone_name' => $b->zone?->name ?? 'غير محدد',
                'subzone_name' => $b->subzone?->name ?? 'غير محدد',
                'parcel_number' => $b->parcel_number,
                'block_number' => $b->block_number,
                'floors_count' => $b->floors->count(),
                'units_count' => $b->units->count(),
                'building_use' => $b->building_use_name ?? 'سكني',
                'building_status' => $b->building_status_name ?? 'قائم',
                'license_number' => $b->license_number,
            ];
        })->filter()->unique('building_id')->values();
    }

    /**
     * Get all residential and commercial units associated with this citizen.
     */
    public function getCitizenUnits(string $idCard): Collection
    {
        $ownerIds = BhmBuildingOwner::where('id_card', $idCard)->pluck('id');
        $buildingIds = BhmBuildingOwner::where('id_card', $idCard)->pluck('building_id')->filter()->unique();

        // 1. Units directly associated with owner
        $directUnits = BhmUnit::whereIn('building_owner_id', $ownerIds)
            ->with(['building.street', 'building.zone'])
            ->get();

        // 2. Units in buildings owned by citizen
        $buildingUnits = BhmUnit::whereIn('building_id', $buildingIds)
            ->with(['building.street', 'building.zone'])
            ->get();

        $allUnits = $directUnits->merge($buildingUnits)->unique('id');

        return $allUnits->map(function ($unit) {
            $usage = 'أخرى';
            if ($unit->residential) {
                $usage = 'وحدة سكنية (شقة)';
            } elseif ($unit->commercial) {
                $usage = 'محل / تجاري';
            } elseif ($unit->industrial) {
                $usage = 'صناعي / ورشة';
            }

            return [
                'unit_id' => $unit->id,
                'unit_number' => $unit->unit_number ?? '1',
                'floor_number' => $unit->floor_number ?? '0',
                'unit_name' => $unit->unit_name ?? ('شقة رقم ' . ($unit->unit_number ?? '1')),
                'unit_type' => $unit->unit_type ?? $usage,
                'usage_label' => $usage,
                'building_id' => $unit->building_id,
                'building_number' => $unit->building_number ?? $unit->building?->building_number,
                'street_name' => $unit->building?->street?->name ?? 'غير محدد',
                'zone_name' => $unit->building?->zone?->name ?? 'بيت حانون',
                'current_situation' => $unit->current_situation ?? 'مأهولة',
                'ownership_type' => $unit->ownership_type ?? 'ملك',
                'is_working' => (bool)$unit->is_working,
            ];
        })->values();
    }

    /**
     * Get all crafts, industries, and economic activities for this citizen.
     */
    public function getCitizenCrafts(string $idCard): Collection
    {
        $economicals = BhmEconomical::where('id_card', $idCard)
            ->orWhereHas('owners', fn($q) => $q->where('id_card', $idCard))
            ->with(['building.street', 'building.zone', 'sector', 'category', 'crafts.craftType', 'owners'])
            ->get();

        return $economicals->map(function ($eco) {
            return [
                'id' => $eco->id,
                'trade_name' => $eco->trade_name ?? $eco->job_formal_name ?? 'منشأة تجارية',
                'formal_name' => $eco->job_formal_name ?? $eco->trade_name,
                'license_number' => $eco->license_number,
                'craft_number' => $eco->craft_number,
                'file_number' => $eco->file_number,
                'is_licensed' => $eco->isLicensed == 1,
                'is_danger' => (bool)$eco->isDanger,
                'sector_name' => $eco->sector?->name ?? 'تجاري / خدماتي',
                'category_name' => $eco->category?->name ?? 'عام',
                'street_name' => $eco->job_street ?? $eco->building?->street?->name ?? 'غير محدد',
                'location' => $eco->job_location ?? 'بيت حانون',
                'work_state' => $eco->work_state ?? ($eco->isLicensed == 1 ? 'ساري العمل' : 'غير مرخص'),
                'mobile' => $eco->job_mobile,
            ];
        })->values();
    }

    /**
     * Get complete dashboard summary stats for a citizen.
     */
    public function getDashboardSummary(string $idCard): array
    {
        $buildings = $this->getCitizenBuildings($idCard);
        $units = $this->getCitizenUnits($idCard);
        $crafts = $this->getCitizenCrafts($idCard);

        return [
            'buildings_count' => $buildings->count(),
            'units_count' => $units->count(),
            'residential_units_count' => $units->where('usage_label', 'وحدة سكنية (شقة)')->count(),
            'commercial_units_count' => $units->where('usage_label', 'محل / تجاري')->count(),
            'crafts_count' => $crafts->count(),
            'licensed_crafts_count' => $crafts->where('is_licensed', true)->count(),
            'buildings' => $buildings,
            'units' => $units,
            'crafts' => $crafts,
        ];
    }
}

