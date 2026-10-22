<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportBhmData extends Command
{
    protected $signature   = 'bhm:import-data {--fresh : Drop and re-import all data}';
    protected $description = 'Import BHM data from erp_bh_bhm database into the main database';

    private $old;

    public function handle()
    {
        $this->old = DB::connection('bhm_old');

        $this->info('🏗️  BHM Data Import Starting...');

        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();

        $this->importLookupTables();
        $this->importBuildings();
        $this->importOwners();
        $this->importFloors();
        $this->importUnits();
        $this->importLicenses();
        $this->importEconomical();
        $this->importCustomers();
        $this->importTreatments();
        $this->importSubscriptions();

        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $this->info('✅ BHM Data Import Completed Successfully!');
    }

    private function importLookupTables(): void
    {
        $this->info('📦 Importing lookup tables...');

        $lookups = [
            ['zones',                    'bhm_zones',                    ['id', 'name', 'created_at', 'updated_at']],
            ['subzones',                 'bhm_subzones',                 ['id', 'zone_id', 'name', 'created_at', 'updated_at']],
            ['streets',                  'bhm_streets',                  ['id', 'name', 'zone_id', 'created_at', 'updated_at']],
            ['regions',                  'bhm_regions',                  ['id', 'name']],
            ['building_types',           'bhm_building_types',           ['id', 'name', 'created_at', 'updated_at']],
            ['building_statuses',        'bhm_building_statuses',        ['id', 'name', 'created_at', 'updated_at']],
            ['building_property_types',  'bhm_building_property_types',  ['id', 'name', 'created_at', 'updated_at']],
            ['building_uses',            'bhm_building_uses',            ['id', 'name', 'created_at', 'updated_at']],
            ['building_materials',       'bhm_building_materials',       ['id', 'name', 'created_at', 'updated_at']],
            ['building_finishes',        'bhm_building_finishes',        ['id', 'name', 'created_at', 'updated_at']],
            ['economical_sectors',       'bhm_economical_sectors',       ['id', 'name', 'created_at', 'updated_at']],
            ['craft_categories',         'bhm_craft_categories',         ['id', 'name', 'created_at', 'updated_at']],
            ['craft_types',              'bhm_craft_types',              ['id', 'name', 'category_id', 'created_at', 'updated_at']],
            ['craft_status',             'bhm_craft_status',             ['id', 'description']],
            ['professions_categories',   'bhm_professions_categories',   ['id', 'name']],
            ['professions',              'bhm_professions',              ['id', 'name', 'category_id']],
            ['departments',              'bhm_departments',              ['id', 'name', 'created_at', 'updated_at']],
        ];

        foreach ($lookups as [$oldTable, $newTable, $columns]) {
            $count = $this->old->table($oldTable)->count();
            if ($count === 0) { $this->line("  ↳ Skipping {$oldTable} (empty)"); continue; }

            DB::table($newTable)->truncate();
            $this->old->table($oldTable)->orderBy('id')->chunk(500, function($rows) use ($newTable, $columns, $oldTable) {
                $mapped = collect($rows)->map(function($r) use ($columns, $oldTable) {
                    $row = (array)$r;
                    if ($oldTable === 'economical_sectors') {
                        $desc = trim($row['description'] ?? '');
                        $row['name'] = !empty($desc) ? $desc : ($row['sector_name'] ?? '');
                    }
                    return collect($row)->only($columns)->toArray();
                })->toArray();
                DB::table($newTable)->insertOrIgnore($mapped);
            });
            $this->line("  ↳ {$newTable}: {$count} records");
        }
    }

    private function importBuildings(): void
    {
        $this->info('🏠 Importing buildings...');
        DB::table('bhm_buildings')->truncate();

        $count = $this->old->table('buildings')->count();
        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $this->old->table('buildings')->orderBy('id')->chunk(500, function($rows) use ($bar) {
            $insert = collect($rows)->map(function($r) {
                return [
                    'id'                      => $r->id,
                    'street_id'               => $r->street_id,
                    'zone_id'                 => $r->zone_id,
                    'subzone_id'              => $r->subzone_id,
                    'building_type_id'        => $r->building_type,
                    'building_status_id'      => $r->building_status_id ?? null,
                    'building_property_type_id' => $r->building_property_type_id ?? null,
                    'supervisor_id'           => $r->supervisor_id ?? null,
                    'file_number'             => $r->file_number,
                    'building_number'         => $r->building_number,
                    'building_name'           => $r->building_name,
                    'block_number'            => $r->block_number,
                    'parcel_number'           => $r->parcel_number,
                    'ownership_notes'         => $r->ownership_notes,
                    'construction_status'     => $r->construction_status,
                    'residential'             => $r->residential,
                    'commercial'              => $r->commercial,
                    'industrial'              => $r->industrial,
                    'educational'             => $r->educational,
                    'cultural'                => $r->cultural,
                    'health'                  => $r->health,
                    'tourism'                 => $r->tourism,
                    'religous'                => $r->religous,
                    'institutions'            => $r->institutions,
                    'other_usage'             => $r->other_usage,
                    'building_usage_notes'    => $r->building_usage_notes,
                    'building_special_case'   => $r->building_special_case,
                    'wall_stone'              => $r->wall_stone,
                    'wall_stone_concrete'     => $r->wall_stone_concrete,
                    'wall_reinforced_concrete'=> $r->wall_reinforced_concrete,
                    'wall_concrete_stone'     => $r->wall_concrete_stone,
                    'wall_sand_stone'         => $r->wall_sand_stone,
                    'wall_other'              => $r->wall_other,
                    'wall_notes'              => $r->wall_notes,
                    'last_floor_concrete'     => $r->last_floor_concrete,
                    'last_floor_asbast'       => $r->last_floor_asbast,
                    'last_floor_carmeed'      => $r->last_floor_carmeed,
                    'last_floor_zenko'        => $r->last_floor_zenko,
                    'last_floor_other'        => $r->last_floor_other,
                    'last_floor_notes'        => $r->last_floor_notes,
                    'out_status'              => $r->out_status,
                    'overall_status'          => $r->overall_status,
                    'overall_status_notes'    => $r->overall_status_notes,
                    'building_date'           => $r->building_date,
                    'finish_qesara'           => $r->finish_qesara,
                    'finish_italian'          => $r->finish_italian,
                    'finish_tiles'            => $r->finish_tiles,
                    'finish_j_stone'          => $r->finish_j_stone ?? null,
                    'finish_granuleet'        => $r->finish_granuleet ?? null,
                    'finish_other'            => $r->finish_other ?? null,
                    'finish_notes'            => $r->finish_notes ?? null,
                    'water_source'            => $r->water_source ?? null,
                    'sewage'                  => $r->sewage,
                    'electricity_source'      => $r->electricity_source ?? null,
                    'elevators_count'         => $r->elevators_count ?? null,
                    'escape_stairs'           => $r->escape_stairs,
                    'collector_id'            => $r->collector_id ?? null,
                    'inspector_id'            => $r->inspector_id ?? null,
                    'historical'              => $r->historical ?? 0,
                    'adding_new_floor'        => $r->adding_new_floor ?? 0,
                    'abandond'                => $r->abandond ?? 0,
                    'session_date'            => $r->sessionDate ?? null,
                    'creation_date'           => $r->creation_date ?? null,
                    'created_by'              => $r->created_by ?? null,
                    'created_at'              => $r->created_at ?? now(),
                    'updated_at'              => $r->updated_at ?? now(),
                ];
            })->toArray();

            DB::table('bhm_buildings')->insertOrIgnore($insert);
            $bar->advance(count($rows));
        });
        $bar->finish(); $this->newLine();

        // Import build_financial
        $this->line('  ↳ Importing build_financial...');
        DB::table('bhm_build_financial')->truncate();
        $this->old->table('build_financial')->orderBy('id')->chunk(500, function($rows) {
            DB::table('bhm_build_financial')->insertOrIgnore(
                collect($rows)->map(fn($r) => [
                    'id'            => $r->id,
                    'building_id'   => $r->build_id,
                    'land_area'     => $r->land_area,
                    'dev_fees'      => $r->dev_fees,
                    'dev_disc_val'  => $r->dev_disc_val,
                    'dev_disc_rat'  => $r->dev_disc_rat,
                    'dev_per_meter' => $r->dev_per_meter,
                    'dev_remains'   => $r->dev_remains,
                    'fees_remains'  => $r->fees_remains,
                    'notes'         => $r->notes,
                    'updated_by'    => $r->updatedBy ?? null,
                    'created_at'    => $r->created_at ?? now(),
                    'updated_at'    => $r->updated_at ?? now(),
                ])->toArray()
            );
        });

        // Import building-use and building-material pivots
        $this->line('  ↳ Importing building pivot tables...');
        DB::table('bhm_building_building_use')->truncate();
        $this->old->table('building_building_use')->orderBy('building_id')->chunk(1000, function($rows) {
            DB::table('bhm_building_building_use')->insertOrIgnore(
                collect($rows)->map(fn($r) => ['building_id' => $r->building_id, 'building_use_id' => $r->building_use_id])->toArray()
            );
        });

        DB::table('bhm_building_building_material')->truncate();
        $this->old->table('building_building_material')->orderBy('building_id')->chunk(1000, function($rows) {
            DB::table('bhm_building_building_material')->insertOrIgnore(
                collect($rows)->map(fn($r) => ['building_id' => $r->building_id, 'building_material_id' => $r->building_material_id])->toArray()
            );
        });
    }

    private function importOwners(): void
    {
        $this->info('👤 Importing building owners...');
        DB::table('bhm_building_owners')->truncate();
        DB::table('bhm_previous_owners')->truncate();

        $this->old->table('building_owners')->orderBy('id')->chunk(500, function($rows) {
            DB::table('bhm_building_owners')->insertOrIgnore(
                collect($rows)->map(fn($r) => [
                    'id'              => $r->id,
                    'building_id'     => $r->building_id,
                    'id_card'         => $r->id_card,
                    'first_name'      => $r->first_name,
                    'second_name'     => $r->second_name,
                    'third_name'      => $r->third_name,
                    'sur_name'        => $r->sur_name,
                    'phone_number'    => $r->phone_number,
                    'is_approve'      => $r->is_approve ?? null,
                    'building_number' => $r->building_number ?? null,
                    'license_form_id' => $r->license_form_id ?? null,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ])->toArray()
            );
        });

        $this->old->table('previous_owners')->orderBy('id')->chunk(500, function($rows) {
            DB::table('bhm_previous_owners')->insertOrIgnore(
                collect($rows)->map(fn($r) => [
                    'id'           => $r->id,
                    'building_id'  => $r->building_id,
                    'id_card'      => $r->id_card,
                    'first_name'   => $r->first_name,
                    'second_name'  => $r->second_name,
                    'third_name'   => $r->third_name,
                    'sur_name'     => $r->sur_name,
                    'phone_number' => $r->phone_number,
                    'created_at'   => now(), 'updated_at' => now(),
                ])->toArray()
            );
        });
        $this->line('  ↳ Owners imported successfully.');
    }

    private function importFloors(): void
    {
        $this->info('🏢 Importing floors...');
        DB::table('bhm_floors_descriptions')->truncate();

        $this->old->table('floors_descriptions')->orderBy('id')->chunk(500, function($rows) {
            DB::table('bhm_floors_descriptions')->insertOrIgnore(
                collect($rows)->map(fn($r) => [
                    'id'                => $r->id,
                    'building_id'       => $r->building_id,
                    'floor_number'      => $r->floor_number,
                    'stores'            => $r->stores,
                    'departments'       => $r->departments,
                    'others'            => $r->others,
                    'total_count'       => $r->total_count,
                    'finish_full'       => $r->finish_full,
                    'finish_partial'    => $r->finish_partial,
                    'finish_none'       => $r->finish_none,
                    'used'              => $r->used,
                    'not_used'          => $r->not_used,
                    'notes'             => $r->notes,
                    'has_cantileaver'   => $r->has_cantileaver,
                    'is_licensed'       => $r->is_licensed,
                    'area'              => $r->area,
                    'lic_fees'          => $r->lic_fees,
                    'lic_fees_discount' => $r->lic_fees_discount,
                    'lic_fees_disc_val' => $r->lic_fees_disc_val,
                    'lic_per_meter'     => $r->lic_per_meter,
                    'got_license'       => $r->got_license,
                    'lic_number'        => $r->lic_number,
                    'licensed_area'     => $r->licensed_area,
                    'license_fees'      => $r->license_fees,
                    'required_pay'      => $r->required_pay,
                    'building_number'   => $r->building_number,
                    'license_form_id'   => $r->license_form_id,
                    'payment_number'    => $r->payment_number,
                    'created_at'        => $r->created_at ?? now(),
                    'updated_at'        => $r->updated_at ?? now(),
                ])->toArray()
            );
        });
    }

    private function importUnits(): void
    {
        $this->info('🚪 Importing units...');
        DB::table('bhm_units')->truncate();

        $this->old->table('units')->orderBy('id')->chunk(500, function($rows) {
            DB::table('bhm_units')->insertOrIgnore(
                collect($rows)->map(fn($r) => [
                    'id'                     => $r->id,
                    'building_id'            => $r->building_id,
                    'floor_id'               => $r->floor_id ?? null,
                    'building_owner_id'      => $r->building_owner_id ?? null,
                    'street_number'          => $r->street_number,
                    'building_number'        => $r->building_number,
                    'floor_number'           => $r->floor_number,
                    'unit_number'            => $r->unit_number,
                    'unit_name'              => $r->unit_name,
                    'unit_type'              => $r->unit_type,
                    'form_number'            => $r->form_number,
                    'ownership_type'         => $r->ownership_type,
                    'ownership_notes'        => $r->ownership_notes,
                    'current_situation'      => $r->current_situation,
                    'current_situation_notes'=> $r->current_situation_notes,
                    'residential'            => $r->residential,
                    'commercial'             => $r->commercial,
                    'industrial'             => $r->industrial,
                    'educational'            => $r->educational,
                    'cultural'               => $r->cultural,
                    'health'                 => $r->health,
                    'tourism'                => $r->tourism,
                    'religous'               => $r->religous,
                    'institutions'           => $r->institutions,
                    'other_usage'            => $r->other_usage,
                    'unit_usage_notes'       => $r->unit_usage_notes,
                    'internal_status'        => $r->internal_status,
                    'external_status'        => $r->external_status,
                    'profession_type'        => $r->profession_type,
                    'profession_name'        => $r->profession_name,
                    'is_working'             => $r->is_working,
                    'has_banner'             => $r->has_banner,
                    'is_lighting_banner'     => $r->is_lighting_banner,
                    'system_number'          => $r->system_number,
                    'water_notes'            => $r->water_notes,
                    'unit_notes'             => $r->unit_notes,
                    'collector_id'           => $r->collector_id,
                    'inspector_id'           => $r->inspector_id,
                    'supervisor_id'          => $r->supervisor_id,
                    'created_by'             => $r->created_by,
                    'creation_date'          => $r->creation_date,
                    'created_at'             => $r->created_at ?? now(),
                    'updated_at'             => $r->updated_at ?? now(),
                ])->toArray()
            );
        });
    }

    private function importLicenses(): void
    {
        $this->info('📋 Importing licenses & reports...');
        DB::table('bhm_license_forms')->truncate();
        DB::table('bhm_regulatory_disclosure_reports')->truncate();
        DB::table('bhm_proof_of_cases')->truncate();

        $this->old->table('license_forms')->orderBy('id')->chunk(200, function($rows) {
            DB::table('bhm_license_forms')->insertOrIgnore(
                collect($rows)->map(fn($r) => [
                    'id'          => $r->id,
                    'building_id' => $r->building_id ?? null,
                    'building_number' => $r->building_number ?? null,
                    'block_number' => $r->block_number ?? null,
                    'parcel_number' => $r->parcel_number ?? null,
                    'region'      => $r->region ?? null,
                    'subject'     => $r->subject ?? null,
                    'status'      => $r->status ?? null,
                    'legal_opinion' => $r->legal_opinion ?? null,
                    'area_opinion'  => $r->area_opinion ?? null,
                    'plan_opinion'  => $r->plan_opinion ?? null,
                    'water_opinion' => $r->water_opinion ?? null,
                    'sewer_opinion' => $r->sewer_opinion ?? null,
                    'collection_opinion' => $r->collection_opinion ?? null,
                    'gis_opinion'   => $r->gis_opinion ?? null,
                    'title_deed_id' => $r->title_deed_id ?? null,
                    'general_site_plan_id' => $r->general_site_plan_id ?? null,
                    'construction_map_id' => $r->construction_map_id ?? null,
                    'undertaking_supervise_id' => $r->undertaking_supervise_id ?? null,
                    'aprobaciones_terceros_id' => $r->aprobaciones_terceros_id ?? null,
                    'attachment_one' => $r->attachment_one ?? null,
                    'created_at' => $r->created_at ?? now(),
                    'updated_at' => $r->updated_at ?? now(),
                ])->toArray()
            );
        });

        $this->old->table('regulatory_disclosure_reports')->orderBy('id')->chunk(200, function($rows) {
            DB::table('bhm_regulatory_disclosure_reports')->insertOrIgnore(
                collect($rows)->map(fn($r) => [
                    'id'          => $r->id,
                    'building_id' => $r->building_id,
                    'license_form_id' => $r->license_form_id,
                    'isproperty'  => $r->isproperty,
                    'isorted'     => $r->isorted,
                    'region'      => $r->region,
                    'location_status' => $r->location_status,
                    'total_coupon_space' => $r->total_coupon_space,
                    'building_area' => $r->building_area,
                    'rebounds_front' => $r->rebounds_front,
                    'rebounds_back' => $r->rebounds_back,
                    'rebounds_right' => $r->rebounds_right,
                    'rebounds_left' => $r->rebounds_left,
                    'construction_ratio' => $r->construction_ratio,
                    'number_floor' => $r->number_floor,
                    'purpose_building_use' => $r->purpose_building_use,
                    'site_on_structural' => $r->site_on_structural,
                    'passes_through_site' => $r->passes_through_site,
                    'territory_regulatory_requirement' => $r->territory_regulatory_requirement,
                    'department_notes' => $r->department_notes,
                    'trust' => $r->trust ?? null,
                    'development_area' => $r->development_area ?? null,
                    'created_at' => $r->created_at ?? now(),
                    'updated_at' => $r->updated_at ?? now(),
                ])->toArray()
            );
        });

        $this->old->table('proof_of_cases')->orderBy('id')->chunk(200, function($rows) {
            DB::table('bhm_proof_of_cases')->insertOrIgnore(
                collect($rows)->map(fn($r) => [
                    'id'           => $r->id,
                    'building_id'  => $r->building_id,
                    'case_number'  => $r->case_number ?? null,
                    'description'  => $r->description ?? null,
                    'status'       => $r->status ?? null,
                    'created_at'   => $r->created_at ?? now(),
                    'updated_at'   => $r->updated_at ?? now(),
                ])->toArray()
            );
        });
    }

    private function importEconomical(): void
    {
        $this->info('🏪 Importing economical activity...');

        // Sync sectors with concise names
        $shortNames = [
            1 => 'خدمات زراعية وبيطرية', 2 => 'حراجة وقطع أخشاب', 3 => 'صيد وتربية أسماك', 4 => 'تعدين واستخراج فحم',
            5 => 'استخراج نفط وغاز', 6 => 'تعدين خامات معادن', 7 => 'محاجر وكسارات', 8 => 'خدمات دعم تعدين',
            9 => 'صناعات غذائية ومخابز', 10 => 'صناعة مشروبات ومياه', 11 => 'صناعة تبغ ومعسل', 12 => 'صناعة منسوجات وسجاد',
            13 => 'صناعة وخياطة ملابس', 14 => 'صناعات جلدية وأحذية', 15 => 'نجارة ومصنوعات خشبية', 16 => 'صناعة ورق وكرتون',
            17 => 'طباعة ونشر ودعاية', 18 => 'مشتقات نفطية ووقود', 19 => 'صناعات كيميائية ومنظفات', 20 => 'صناعات دوائية وصيدلانية',
            21 => 'صناعة بلاستيك ومطاط', 22 => 'حجر ورخام وبلوك', 23 => 'صناعات معدنية وحديد', 24 => 'حدادة وألمنيوم ومخارط',
            25 => 'صناعات إلكترونية وبصرية', 26 => 'صناعة معدات كهربائية', 27 => 'صناعة آلات ومعدات', 28 => 'صناعة مركبات ومقطورات',
            29 => 'معدات نقل أخرى', 30 => 'صناعة وتجارة أثاث', 31 => 'صناعات تحويلية ومجوهرات', 32 => 'صيانة وتركيب معدات',
            33 => 'طاقة وكهرباء وتكييف', 34 => 'معالجة وتوزيع مياه', 35 => 'خدمات صرف صحي', 36 => 'تدوير ومعالجة نفايات',
            37 => 'إدارة ومعالجة بيئية', 38 => 'مقاولات وبناء مباني', 39 => 'طرق وبنية تحتية', 40 => 'تشطيبات وديكور ومقاولات',
            41 => 'تجارة وصيانة سيارات', 42 => 'تجارة جملة وتوزيع', 43 => 'محلات ومتاجر تجزئة', 44 => 'نقل بري وركاب',
            45 => 'خدمات نقل مائي', 46 => 'خدمات طيران ونقل', 47 => 'مستودعات وتبريد وتخزين', 48 => 'بريد وشحن طرود',
            49 => 'فنادق وشقق فندقية', 50 => 'مطاعم ومقاهي وبوفيهات', 51 => 'نشر وتوزيع كتب', 52 => 'إنتاج فني وإعلامي',
            53 => 'بث إذاعي وتلفزيوني', 54 => 'اتصالات وشبكات إنترنت', 55 => 'برمجيات وتقنية معلومات', 56 => 'خدمات معلومات وصحافة',
            57 => 'بنوك وخدمات مالية', 58 => 'شركات تأمين وتكافل', 59 => 'صرافة وأسواق مالية', 60 => 'مكاتب ووساطة عقارية',
            61 => 'محاماة واستشارات قانونية', 62 => 'استشارات إدارية وتنظيمية', 63 => 'مكاتب هندسة ومساحة', 64 => 'أبحاث وتطوير علمي',
            65 => 'دعاية وإعلان وتسويق', 66 => 'استوديوهات وديكور وترجمة', 67 => 'عيادات وخدمات بيطرية', 68 => 'تأجير سيارات ومناسبات',
            69 => 'مكاتب تشغيل وتوظيف', 70 => 'سياحة وسفر وحج', 71 => 'خدمات أمن وحراسة', 72 => 'تنظيف وصيانة مباني',
            73 => 'مكاتب خدمات عامة', 74 => 'مؤسسات وهيئات حكومية', 75 => 'تعليم ومدارس ومراكز', 76 => 'مستشفيات وعيادات ومختبرات',
            77 => 'دور رعاية وإيواء', 78 => 'حضانات ورعاية اجتماعية', 79 => 'فنون ومسارح وترفيه', 80 => 'متاحف ومكتبات عامة',
            81 => 'ألعاب ترفيهية وكازينو', 82 => 'نواد وصالات وقاعات', 83 => 'جمعيات ومساجد ونقابات', 84 => 'صيانة أجهزة وإلكترونيات',
            85 => 'صالونات وعناية شخصية', 86 => 'خدمات عمالة منزلية', 87 => 'أنشطة أسرية خاصة', 88 => 'هيئات دولية وقنصليات',
        ];
        try {
            $oldSectors = $this->old->table('economical_sectors')->get();
            foreach ($oldSectors as $s) {
                DB::table('bhm_economical_sectors')->updateOrInsert(
                    ['id' => $s->id],
                    [
                        'name'           => $shortNames[$s->id] ?? ($s->description ?: $s->sector_name),
                        'sector_name'    => $s->sector_name ?? '',
                        'section_number' => $s->section_number ?? '',
                        'description'    => $s->description ?? '',
                    ]
                );
            }
        } catch (\Exception $e) {}

        DB::table('bhm_economical')->truncate();
        DB::table('bhm_economical_owners')->truncate();

        // Pre-load owners to map id_card and name
        $owners = $this->old->table('economical_owners')->orderBy('id')->get();
        $ownersMap = [];
        foreach ($owners as $o) {
            if (!isset($ownersMap[$o->economical_id])) {
                $fullName = trim(implode(' ', array_filter([$o->first_name, $o->second_name, $o->third_name, $o->sur_name])));
                $ownersMap[$o->economical_id] = [
                    'id_card' => $o->id_card,
                    'name'    => $fullName,
                ];
            }
        }

        $this->old->table('economical')->orderBy('id')->chunk(500, function($rows) use ($ownersMap) {
            DB::table('bhm_economical')->insertOrIgnore(
                collect($rows)->map(function($r) use ($ownersMap) {
                    $getDate = ($r->get_date === '0000-00-00' || empty($r->get_date)) ? null : $r->get_date;
                    $creationDate = ($r->creation_date === '0000-00-00' || empty($r->creation_date)) ? null : $r->creation_date;
                    $ownerInfo = $ownersMap[$r->id] ?? null;

                    return [
                        'id'                => $r->id,
                        'municipality_id'   => $r->municipality_id ?? null,
                        'street_id'         => $r->street_id ?? null,
                        'building_number'   => $r->building_number ?? null,
                        'floor_number'      => $r->floor_number ?? null,
                        'unit_number'       => $r->unit_number ?? null,
                        'zone_id'           => $r->zone_id ?? null,
                        'subzone_id'        => $r->subzone_id ?? null,
                        'parcel_number'     => $r->parcel_number ?? null,
                        'block_number'      => $r->block_number ?? null,
                        'job_formal_name'   => $r->job_formal_name ?? null,
                        'job_use_name'      => $r->job_use_name ?? null,
                        'old_job_number'    => $r->old_job_number ?? null,
                        'job_location'      => $r->job_location ?? null,
                        'job_street'        => $r->job_street ?? null,
                        'job_phone'         => $r->job_phone ?? null,
                        'job_mobile'        => $r->job_mobile ?? null,
                        'website'           => $r->website ?? null,
                        'email'             => $r->email ?? null,
                        'whatsapp'          => $r->whatsapp ?? null,
                        'facebook'          => $r->facebook ?? null,
                        'instagram'         => $r->instagram ?? null,
                        'twitter'           => $r->twitter ?? null,
                        'ownership_type'    => $r->ownership_type ?? null,
                        'ownership_note'    => $r->ownership_note ?? null,
                        'work_state'        => $r->work_state ?? null,
                        'job_sector_id'     => $r->job_sector_id ?? null,
                        'job_description'   => $r->job_description ?? null,
                        'has_branch'        => $r->has_branch ?? null,
                        'branch_number'     => $r->branch_number ?? null,
                        'is_main_branch'    => $r->is_main_branch ?? null,
                        'male_employee'     => $r->male_employee ?? null,
                        'female_employee'   => $r->female_employee ?? null,
                        'info_from'         => $r->info_from ?? null,
                        'info_from_note'    => $r->info_from_note ?? null,
                        'info_result'       => $r->info_result ?? null,
                        'refuse_reason'     => $r->refuse_reason ?? null,
                        'collector_id'      => $r->collector_id ?? null,
                        'supervisor_id'     => $r->supervisor_id ?? null,
                        'get_date'          => $getDate,
                        'created_by'        => $r->created_by ?? null,
                        'creation_date'     => $creationDate,
                        'craft_number'      => $r->craft_number ?? null,
                        'bulding_id'        => $r->bulding_id ?? null,
                        'building_id'       => $r->bulding_id ?? null,
                        'file_number'       => $r->file_number ?? null,
                        'license_number'    => $r->license_number ?? null,
                        'type_property'     => $r->type_property ?? null,
                        'notes'             => $r->notes ?? null,
                        'created_at'        => $r->created_at ?? now(),
                        'updated_at'        => $r->updated_at ?? now(),
                        'building_owner_id' => $r->building_owner_id ?? null,
                        'unit_id'           => $r->unit_id ?? null,
                        'isDanger'          => $r->isDanger ?? null,
                        'isLicensed'        => $r->isLicensed ?? null,
                        'isActive'          => $r->isActive ?? 2,
                        'craft_category_id' => $r->craft_category_id ?? null,
                        'craft_type_id'     => $r->craft_type_id ?? null,
                        'approved'          => $r->approved ?? null,
                        'id_card'           => $ownerInfo['id_card'] ?? null,
                        'name'              => $ownerInfo['name'] ?? null,
                        'trade_name'        => $r->job_formal_name ?: ($r->job_use_name ?: null),
                    ];
                })->toArray()
            );
        });

        $this->old->table('economical_owners')->orderBy('id')->chunk(500, function($rows) {
            DB::table('bhm_economical_owners')->insertOrIgnore(
                collect($rows)->map(fn($r) => [
                    'id'           => $r->id,
                    'economical_id'=> $r->economical_id ?? null,
                    'id_card'      => $r->id_card ?? null,
                    'first_name'   => $r->first_name ?? null,
                    'second_name'  => $r->second_name ?? null,
                    'third_name'   => $r->third_name ?? null,
                    'sur_name'     => $r->sur_name ?? null,
                    'phone'        => $r->phone_number ?? null,
                    'created_at'   => $r->created_at ?? now(),
                    'updated_at'   => $r->updated_at ?? now(),
                ])->toArray()
            );
        });
    }

    private function importCustomers(): void
    {
        $this->info('👥 Importing customers...');
        DB::table('bhm_customers')->truncate();
        DB::table('bhm_customer_pens')->truncate();

        $this->old->table('customers')->orderBy('id')->chunk(500, function($rows) {
            DB::table('bhm_customers')->insertOrIgnore(
                collect($rows)->map(fn($r) => [
                    'id'         => $r->id,
                    'name'       => $r->name ?? null,
                    'id_no'      => $r->id_number ?? null,
                    'mobile'     => $r->mobile ?? null,
                    'telephone'  => $r->telephone ?? null,
                    'email'      => $r->email ?? null,
                    'address'    => $r->address ?? null,
                    'created_at' => $r->created_at ?? now(),
                    'updated_at' => $r->updated_at ?? now(),
                ])->toArray()
            );
        });

        $this->old->table('customer_pens')->orderBy('id')->chunk(500, function($rows) {
            DB::table('bhm_customer_pens')->insertOrIgnore(
                collect($rows)->map(fn($r) => [
                    'id'          => $r->id,
                    'name'        => $r->name ?? null,
                    'id_no'       => $r->id_no ?? null,
                    'mobile'      => $r->mobile ?? null,
                    'telephone'   => $r->telephone ?? null,
                    'email'       => $r->email ?? null,
                    'address'     => $r->address ?? null,
                    'first_name'  => $r->first_name ?? null,
                    'second_name' => $r->second_name ?? null,
                    'third_name'  => $r->third_name ?? null,
                    'sur_name'    => $r->sur_name ?? null,
                    'created_at'  => $r->created_at ?? now(),
                    'updated_at'  => $r->updated_at ?? now(),
                ])->toArray()
            );
        });
    }

    private function importTreatments(): void
    {
        $this->info('📝 Importing treatments...');
        DB::table('bhm_treatments')->truncate();

        $this->old->table('treatments')->orderBy('id')->chunk(200, function($rows) {
            DB::table('bhm_treatments')->insertOrIgnore(
                collect($rows)->map(fn($r) => [
                    'id'            => $r->id,
                    'department_id' => $r->department_id,
                    'name'          => $r->name ?? null,
                    'description'   => $r->description ?? null,
                    'status'        => $r->status ?? null,
                    'created_at'    => $r->created_at ?? now(),
                    'updated_at'    => $r->updated_at ?? now(),
                ])->toArray()
            );
        });
    }

    private function importSubscriptions(): void
    {
        $this->info('💳 Importing subscriptions...');
        DB::table('bhm_subscriptions')->truncate();

        $this->old->table('subscriptions')->orderBy('id')->chunk(500, function($rows) {
            DB::table('bhm_subscriptions')->insertOrIgnore(
                collect($rows)->map(fn($r) => [
                    'id'               => $r->id,
                    'building_id'      => $r->building_id ?? null,
                    'building_owner_id'=> $r->building_owner_id ?? null,
                    'id_number'        => $r->id_number ?? null,
                    'subscriber_name'  => $r->subscriber_name ?? null,
                    'year'             => $r->year ?? null,
                    'amount'           => $r->amount ?? null,
                    'paid'             => $r->paid ?? null,
                    'remaining'        => $r->remaining ?? null,
                    'status'           => $r->status ?? null,
                    'notes'            => $r->notes ?? null,
                    'created_at'       => $r->created_at ?? now(),
                    'updated_at'       => $r->updated_at ?? now(),
                ])->toArray()
            );
        });

        // Subscription-Unit pivot
        $this->old->table('subscription_unit')->orderBy('subscription_id')->chunk(1000, function($rows) {
            DB::table('bhm_subscription_unit')->insertOrIgnore(
                collect($rows)->map(fn($r) => ['subscription_id' => $r->subscription_id, 'unit_id' => $r->unit_id])->toArray()
            );
        });
    }
}
