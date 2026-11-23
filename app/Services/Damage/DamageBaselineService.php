<?php

namespace App\Services\Damage;

use App\Models\Damage\DamageRecord;
use App\Models\Damage\PricingCriteria;
use App\Models\Damage\DamageAuditLog;
use App\Models\Buildings\BhmBuilding;
use App\Models\Buildings\BhmStreet;
use App\Models\Buildings\BhmZone;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DamageBaselineService
{
    /**
     * Run full baseline initialization
     */
    public function initializeAll(): array
    {
        $buildingsCount = $this->initializeBuildings();
        $streetsCount = $this->initializeStreets();
        $economicCount = $this->initializeEconomicFacilities();

        return [
            'buildings' => $buildingsCount,
            'streets' => $streetsCount,
            'economic' => $economicCount,
            'total' => $buildingsCount + $streetsCount + $economicCount,
        ];
    }

    /**
     * Bulk initialize buildings from bhm_buildings
     */
    public function initializeBuildings(?int $limit = null): int
    {
        $concreteCriteria = PricingCriteria::where('category', 'building_structure')
            ->where('name_ar', 'LIKE', '%باطون%')
            ->first();
        $asbestosCriteria = PricingCriteria::where('category', 'building_structure')
            ->where('name_ar', 'LIKE', '%أسبست%')
            ->first();
        $facilityCriteria = PricingCriteria::where('sector', 'public_facilities')->first();

        $query = DB::table('bhm_buildings')
            ->select(
                'bhm_buildings.*',
                'bhm_building_owners.id_card as owner_id_card',
                'bhm_building_owners.first_name as owner_first',
                'bhm_building_owners.second_name as owner_second',
                'bhm_building_owners.third_name as owner_third',
                'bhm_building_owners.sur_name as owner_sur',
                'bhm_building_owners.phone_number as owner_phone'
            )
            ->leftJoin('bhm_building_owners', function ($join) {
                $join->on('bhm_buildings.id', '=', 'bhm_building_owners.building_id')
                    ->whereRaw('bhm_building_owners.id = (SELECT MIN(id) FROM bhm_building_owners WHERE bhm_building_owners.building_id = bhm_buildings.id)');
            });

        if ($limit) {
            $query->limit($limit);
        }

        $imported = 0;
        $year = date('Y');

        $query->chunk(500, function ($buildings) use (&$imported, $concreteCriteria, $asbestosCriteria, $facilityCriteria, $year) {
            $recordsToInsert = [];
            $now = now()->toDateTimeString();

            foreach ($buildings as $b) {
                // Check if already in damage records
                $exists = DB::table('dams_damage_records')
                    ->where('entity_type', 'building')
                    ->where('entity_id', $b->id)
                    ->exists();

                if ($exists) {
                    continue;
                }

                $imported++;
                $recordNumber = 'DAM-' . $year . '-' . sprintf('%06d', $b->id);

                // Detect Sector & Facility Type
                $sector = 'real_estate';
                $facilityType = null;
                $operatingEntity = null;

                if (!empty($b->educational) && $b->educational == 1) {
                    $sector = 'public_facilities';
                    $facilityType = 'school';
                    $operatingEntity = 'وزارة التربية والتعليم / الأونروا';
                } elseif (!empty($b->health) && $b->health == 1) {
                    $sector = 'public_facilities';
                    $facilityType = 'hospital';
                    $operatingEntity = 'وزارة الصحة / المؤسسات الأهلية';
                } elseif (!empty($b->religous) && $b->religous == 1) {
                    $sector = 'public_facilities';
                    $facilityType = 'mosque';
                    $operatingEntity = 'وزارة الأوقاف والشؤون الدينية';
                } elseif (!empty($b->institutions) && $b->institutions == 1) {
                    $sector = 'public_facilities';
                    $facilityType = 'community_center';
                } elseif (!empty($b->commercial) && $b->commercial == 1 && empty($b->residential)) {
                    $sector = 'economic_crafts';
                }

                // Structure Type
                $structureType = 'reinforced_concrete';
                $activeCriteria = $concreteCriteria;

                if (!empty($b->last_floor_asbast) && $b->last_floor_asbast == 1) {
                    $structureType = 'asbestos';
                    $activeCriteria = $asbestosCriteria ?? $concreteCriteria;
                }

                if ($sector === 'public_facilities' && $facilityCriteria) {
                    $activeCriteria = $facilityCriteria;
                }

                // Dimensions & Area
                $area = (float)($b->area ?? 0);
                if ($area <= 0) {
                    $area = 160.00; // Average residential footprint
                }
                $floors = 2; // Baseline standard floors
                $unitsCount = 2;

                // Pricing Calculation
                $unitPriceUsd = $activeCriteria ? (float)$activeCriteria->unit_price_usd : 350.00;
                $unitPriceIls = $activeCriteria ? (float)$activeCriteria->unit_price_ils : 1295.00;
                $totalArea = $area * $floors;
                $estimatedUsd = round($totalArea * $unitPriceUsd, 2);
                $estimatedIls = round($totalArea * $unitPriceIls, 2);

                // Owner Name
                $ownerName = null;
                if (!empty($b->owner_first)) {
                    $ownerName = trim($b->owner_first . ' ' . $b->owner_second . ' ' . $b->owner_third . ' ' . $b->owner_sur);
                }

                $title = $b->building_name ?: ($ownerName ? 'مبنى ' . $ownerName : 'مبنى #' . $b->building_number);
                $certUuid = (string) Str::uuid();

                $recordsToInsert[] = [
                    'record_number' => $recordNumber,
                    'sector' => $sector,
                    'entity_type' => 'building',
                    'entity_id' => $b->id,
                    'zone_id' => $b->zone_id,
                    'subzone_id' => $b->subzone_id,
                    'street_id' => $b->street_id,
                    'building_id' => $b->id,
                    'building_number' => $b->building_number,
                    'file_number' => $b->file_number,
                    'block_number' => $b->block_number,
                    'parcel_number' => $b->parcel_number,
                    'name' => $title,
                    'damage_level' => 'total_destruction',
                    'operational_status' => 'destroyed',
                    'area_sqm' => $area,
                    'floors_count' => $floors,
                    'units_count' => $unitsCount,
                    'structure_type' => $structureType,
                    'facility_type' => $facilityType,
                    'operating_entity' => $operatingEntity,
                    'pricing_criteria_id' => $activeCriteria?->id,
                    'estimated_cost_usd' => $estimatedUsd,
                    'estimated_cost_ils' => $estimatedIls,
                    'pricing_breakdown' => json_encode([
                        'base_unit_price_usd' => $unitPriceUsd,
                        'total_built_area_sqm' => $totalArea,
                        'floors' => $floors,
                        'damage_rate' => 1.0,
                    ]),
                    'workflow_status' => 'draft',
                    'citizen_national_id' => $b->owner_id_card,
                    'citizen_name' => $ownerName,
                    'citizen_phone' => $b->owner_phone,
                    'certificate_uuid' => $certUuid,
                    'certificate_qr_hash' => hash('sha256', $certUuid . '-' . config('app.key')),
                    'initial_assessment_date' => date('Y-m-d'),
                    'is_baseline_auto' => true,
                    'notes' => 'تم الإدراج التأسيسي التلقائي بحالة مهدم كلياً بناء على التأسيس الميداني الأولي',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            if (!empty($recordsToInsert)) {
                DB::table('dams_damage_records')->insert($recordsToInsert);
            }
        });

        return $imported;
    }

    /**
     * Bulk initialize streets & infrastructure from bhm_streets
     */
    public function initializeStreets(?int $limit = null): int
    {
        $asphaltCriteria = PricingCriteria::where('category', 'road_asphalt')->first();
        $waterCriteria = PricingCriteria::where('category', 'water_network')->first();
        $sewageCriteria = PricingCriteria::where('category', 'sewage_network')->first();
        $elecCriteria = PricingCriteria::where('category', 'electricity_network')->first();

        $query = DB::table('bhm_streets');
        if ($limit) {
            $query->limit($limit);
        }

        $imported = 0;
        $year = date('Y');

        $query->chunk(200, function ($streets) use (&$imported, $asphaltCriteria, $waterCriteria, $sewageCriteria, $elecCriteria, $year) {
            $recordsToInsert = [];
            $now = now()->toDateTimeString();

            foreach ($streets as $st) {
                $exists = DB::table('dams_damage_records')
                    ->where('entity_type', 'street')
                    ->where('entity_id', $st->id)
                    ->exists();

                if ($exists) {
                    continue;
                }

                $imported++;
                $recordNumber = 'DAM-' . $year . '-ST' . sprintf('%04d', $st->id);

                // Default estimated street dimensions
                $length = 350.00; // meters
                $width = 8.00; // meters
                $roadArea = $length * $width;

                $roadCostUsd = $roadArea * ($asphaltCriteria ? (float)$asphaltCriteria->unit_price_usd : 45.00);
                $waterCostUsd = $length * ($waterCriteria ? (float)$waterCriteria->unit_price_usd : 55.00);
                $sewageCostUsd = $length * ($sewageCriteria ? (float)$sewageCriteria->unit_price_usd : 75.00);
                $elecCostUsd = $length * ($elecCriteria ? (float)$elecCriteria->unit_price_usd : 40.00);

                $totalCostUsd = round($roadCostUsd + $waterCostUsd + $sewageCostUsd + $elecCostUsd, 2);
                $totalCostIls = round($totalCostUsd * 3.70, 2);

                $streetTitle = $st->street_name ? ('شارع ' . $st->street_name) : ('شارع رقم #' . ($st->street_number ?: $st->id));
                $certUuid = (string) Str::uuid();

                $recordsToInsert[] = [
                    'record_number' => $recordNumber,
                    'sector' => 'infrastructure',
                    'entity_type' => 'street',
                    'entity_id' => $st->id,
                    'zone_id' => $st->zone_id,
                    'subzone_id' => $st->subzone_id,
                    'street_id' => $st->id,
                    'name' => $streetTitle,
                    'damage_level' => 'total_destruction',
                    'operational_status' => 'out_of_service',
                    'length_meters' => $length,
                    'width_meters' => $width,
                    'area_sqm' => $roadArea,
                    'road_type' => 'asphalt',
                    'associated_infrastructure' => json_encode([
                        'water_network' => true,
                        'sewage_network' => true,
                        'electricity_lighting' => true,
                        'storm_drainage' => true,
                        'manholes_count' => (int)($length / 40),
                    ]),
                    'pricing_criteria_id' => $asphaltCriteria?->id,
                    'estimated_cost_usd' => $totalCostUsd,
                    'estimated_cost_ils' => $totalCostIls,
                    'pricing_breakdown' => json_encode([
                        'road_paving_usd' => $roadCostUsd,
                        'water_network_usd' => $waterCostUsd,
                        'sewage_network_usd' => $sewageCostUsd,
                        'electricity_lighting_usd' => $elecCostUsd,
                        'total_length_m' => $length,
                        'road_area_sqm' => $roadArea,
                    ]),
                    'workflow_status' => 'draft',
                    'certificate_uuid' => $certUuid,
                    'certificate_qr_hash' => hash('sha256', $certUuid . '-' . config('app.key')),
                    'initial_assessment_date' => date('Y-m-d'),
                    'is_baseline_auto' => true,
                    'notes' => 'تأسيس أولي لشارع وبنية تحتية متضررة بشكل شامل',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            if (!empty($recordsToInsert)) {
                DB::table('dams_damage_records')->insert($recordsToInsert);
            }
        });

        return $imported;
    }

    /**
     * Bulk initialize economic & craft facilities from bhm_economical
     */
    public function initializeEconomicFacilities(?int $limit = null): int
    {
        $craftCriteria = PricingCriteria::where('sector', 'economic_crafts')->first();

        $query = DB::table('bhm_economical');
        if ($limit) {
            $query->limit($limit);
        }

        $imported = 0;
        $year = date('Y');

        $query->chunk(200, function ($crafts) use (&$imported, $craftCriteria, $year) {
            $recordsToInsert = [];
            $now = now()->toDateTimeString();

            foreach ($crafts as $c) {
                $exists = DB::table('dams_damage_records')
                    ->where('entity_type', 'craft')
                    ->where('entity_id', $c->id)
                    ->exists();

                if ($exists) {
                    continue;
                }

                $imported++;
                $recordNumber = 'DAM-' . $year . '-CR' . sprintf('%04d', $c->id);

                $area = 80.00; // Average commercial shop area
                $unitPriceUsd = $craftCriteria ? (float)$craftCriteria->unit_price_usd : 220.00;
                $estimatedUsd = round($area * $unitPriceUsd, 2);
                $estimatedIls = round($estimatedUsd * 3.70, 2);

                $certUuid = (string) Str::uuid();
                $title = $c->trade_name ?: ($c->job_formal_name ?: ('منشأة ' . ($c->name ?: '#' . $c->id)));

                $recordsToInsert[] = [
                    'record_number' => $recordNumber,
                    'sector' => 'economic_crafts',
                    'entity_type' => 'craft',
                    'entity_id' => $c->id,
                    'zone_id' => $c->zone_id ?: null,
                    'subzone_id' => $c->subzone_id ?: null,
                    'street_id' => $c->street_id ?: null,
                    'building_number' => $c->building_number,
                    'unit_number' => $c->unit_number,
                    'name' => $title,
                    'damage_level' => 'total_destruction',
                    'operational_status' => 'destroyed',
                    'area_sqm' => $area,
                    'floors_count' => 1,
                    'units_count' => 1,
                    'structure_type' => 'steel_hanger',
                    'pricing_criteria_id' => $craftCriteria?->id,
                    'estimated_cost_usd' => $estimatedUsd,
                    'estimated_cost_ils' => $estimatedIls,
                    'pricing_breakdown' => json_encode([
                        'area_sqm' => $area,
                        'rate_usd' => $unitPriceUsd,
                        'damage_rate' => 1.0,
                    ]),
                    'workflow_status' => 'draft',
                    'citizen_national_id' => $c->id_card,
                    'citizen_name' => $c->name,
                    'citizen_phone' => $c->job_mobile ?: $c->job_phone,
                    'certificate_uuid' => $certUuid,
                    'certificate_qr_hash' => hash('sha256', $certUuid . '-' . config('app.key')),
                    'initial_assessment_date' => date('Y-m-d'),
                    'is_baseline_auto' => true,
                    'notes' => 'حصر أولي لمنشأة اقتصادية/حرفية متضررة كلياً',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            if (!empty($recordsToInsert)) {
                DB::table('dams_damage_records')->insert($recordsToInsert);
            }
        });

        return $imported;
    }

    /**
     * Recalculate estimated costs for a specific record based on active criteria
     */
    public function recalculateCost(DamageRecord $record): void
    {
        $criteria = $record->pricingCriteria;
        if (!$criteria) {
            $criteria = PricingCriteria::where('sector', $record->sector)->first();
        }

        if (!$criteria) {
            return;
        }

        $multiplier = $criteria->damage_multipliers[$record->damage_level] ?? 1.0;
        $unitUsd = (float)$criteria->unit_price_usd;
        $unitIls = (float)$criteria->unit_price_ils;

        if ($record->sector === 'infrastructure' && $record->length_meters > 0) {
            $roadArea = (float)$record->length_meters * ((float)$record->width_meters ?: 8.0);
            $estimatedUsd = round(($roadArea * $unitUsd) * $multiplier, 2);
            $estimatedIls = round(($roadArea * $unitIls) * $multiplier, 2);
        } else {
            $totalArea = (float)$record->area_sqm * ((int)$record->floors_count ?: 1);
            if ($totalArea <= 0) {
                $totalArea = 150.0;
            }
            $estimatedUsd = round(($totalArea * $unitUsd) * $multiplier, 2);
            $estimatedIls = round(($totalArea * $unitIls) * $multiplier, 2);
        }

        $record->update([
            'estimated_cost_usd' => $estimatedUsd,
            'estimated_cost_ils' => $estimatedIls,
            'pricing_breakdown' => [
                'criteria_id' => $criteria->id,
                'criteria_name' => $criteria->name_ar,
                'unit_price_usd' => $unitUsd,
                'multiplier' => $multiplier,
                'recalculated_at' => now()->toDateTimeString(),
            ],
        ]);
    }
}

