<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Engineering Pricing Criteria (معايير التسعير والكميات الهندسية)
        Schema::create('dams_pricing_criteria', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');
            $table->string('name_en')->nullable();
            $table->string('sector'); // municipal_assets, infrastructure, real_estate, public_facilities, economic_crafts
            $table->string('category')->nullable(); // building_structure, road_asphalt, road_interlock, water_network, sewage_network, electricity_network, etc.
            $table->string('unit_of_measure'); // sqm, linear_meter, item, lump_sum
            $table->decimal('unit_price_usd', 12, 2)->default(0);
            $table->decimal('unit_price_ils', 12, 2)->default(0);
            $table->json('damage_multipliers')->nullable(); // e.g. {"total_destruction": 1.0, "severe_damage": 0.65, "partial_damage": 0.35, "minor_damage": 0.15}
            $table->string('formula_type')->default('area_floors'); // area_floors, road_dimension, network_length, fixed_asset, manual
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->index('sector');
            $table->index('is_active');
        });

        // 2. Master Damage Assessment Records (سجل حصر الأضرار الرئيسي)
        Schema::create('dams_damage_records', function (Blueprint $table) {
            $table->id();
            $table->string('record_number', 50)->unique();
            $table->string('sector'); // municipal_assets, infrastructure, real_estate, public_facilities, economic_crafts
            $table->string('entity_type'); // building, unit, street, asset, craft, facility
            $table->unsignedBigInteger('entity_id')->nullable();

            // Geographical & Administrative location
            $table->unsignedBigInteger('zone_id')->nullable();
            $table->unsignedBigInteger('subzone_id')->nullable();
            $table->unsignedBigInteger('street_id')->nullable();
            $table->unsignedBigInteger('building_id')->nullable();
            $table->string('building_number', 50)->nullable();
            $table->string('unit_number', 50)->nullable();
            $table->string('file_number', 50)->nullable(); // archive file number
            $table->string('block_number', 50)->nullable(); // حوض
            $table->string('parcel_number', 50)->nullable(); // قطعة / قسيمة
            $table->string('name')->nullable(); // Title/Name of the entity

            // Coordinates & GIS
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->json('geojson_geometry')->nullable();

            // Damage Level & Operational Status
            $table->string('damage_level')->default('total_destruction'); // total_destruction, severe_damage, partial_damage, minor_damage, intact
            $table->string('operational_status')->default('destroyed'); // destroyed, out_of_service, partially_working, operational

            // Dimensional & Engineering Quantities
            $table->decimal('area_sqm', 12, 2)->nullable()->default(0);
            $table->integer('floors_count')->nullable()->default(1);
            $table->integer('units_count')->nullable()->default(1);
            $table->decimal('length_meters', 10, 2)->nullable()->default(0);
            $table->decimal('width_meters', 10, 2)->nullable()->default(0);
            $table->string('road_type', 50)->nullable(); // asphalt, interlock, dirt
            $table->json('associated_infrastructure')->nullable(); // water, sewage, electricity, storm, manholes
            $table->string('structure_type', 50)->nullable(); // reinforced_concrete, asbestos, steel_hanger, stone, other
            $table->string('facility_type', 50)->nullable(); // school, hospital, clinic, mosque, community_center, municipal_building
            $table->string('capacity', 100)->nullable();
            $table->string('operating_entity', 150)->nullable(); // municipality, unrwa, government, private, waqf

            // Financial & Engineering Pricing
            $table->unsignedBigInteger('pricing_criteria_id')->nullable();
            $table->decimal('estimated_cost_usd', 14, 2)->nullable()->default(0);
            $table->decimal('estimated_cost_ils', 14, 2)->nullable()->default(0);
            $table->json('pricing_breakdown')->nullable();

            // Workflow & Approval
            $table->string('workflow_status')->default('draft'); // draft, engineering_review, final_approved, rejected
            $table->unsignedBigInteger('audit_created_by')->nullable();
            $table->unsignedBigInteger('audit_verified_by')->nullable();
            $table->unsignedBigInteger('audit_approved_by')->nullable();
            $table->date('initial_assessment_date')->nullable();
            $table->date('engineering_inspection_date')->nullable();
            $table->date('approval_date')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('engineering_notes')->nullable();
            $table->text('notes')->nullable();

            // Citizen & Certificate Data
            $table->string('citizen_national_id', 20)->nullable();
            $table->string('citizen_name', 191)->nullable();
            $table->string('citizen_phone', 30)->nullable();
            $table->uuid('certificate_uuid')->nullable()->unique();
            $table->string('certificate_qr_hash', 64)->nullable();
            $table->timestamp('certificate_issued_at')->nullable();
            $table->integer('certificate_downloads_count')->default(0);

            // Origin Flag
            $table->boolean('is_baseline_auto')->default(false);

            $table->timestamps();
            $table->softDeletes();

            // Indexes for ultra-fast query performance
            $table->index('sector');
            $table->index('entity_type');
            $table->index('damage_level');
            $table->index('workflow_status');
            $table->index('zone_id');
            $table->index('street_id');
            $table->index('building_id');
            $table->index('citizen_national_id');
            $table->index('file_number');
            $table->index('block_number');
            $table->index('parcel_number');
            $table->index(['latitude', 'longitude']);
            $table->index('estimated_cost_usd');
            $table->index('is_baseline_auto');
        });

        // 3. Structural Audit Logs (سجل التتبع والتدقيق الهيكلي)
        Schema::create('dams_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('record_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action'); // created, bulk_imported, damage_level_changed, pricing_recalculated, workflow_transition, notes_added
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('notes')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('record_id');
            $table->index('user_id');
            $table->index('action');
            $table->index('created_at');
        });

        // 4. Media & Engineering Attachments (الأرشيف المرئي والهندسي)
        Schema::create('dams_media_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('record_id');
            $table->string('attachment_type'); // before_photo, after_photo, engineering_drawing, permit_document, field_report, video
            $table->string('title')->nullable();
            $table->string('file_path');
            $table->string('file_name');
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->unsignedBigInteger('central_archive_file_id')->nullable();
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamps();

            $table->index('record_id');
            $table->index('attachment_type');
        });

        // 5. Donor & Institutional Accounts (سجل المنظمات والجهات المانحة)
        Schema::create('dams_donor_organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');
            $table->string('name_en')->nullable();
            $table->string('organization_type'); // un_agency, international_ngo, local_ngo, ministry, donor, other
            $table->string('contact_person')->nullable();
            $table->string('email')->unique();
            $table->string('phone', 30)->nullable();
            $table->string('password')->nullable();
            $table->string('status')->default('active'); // active, suspended, pending
            $table->string('api_key', 64)->unique()->nullable();
            $table->json('allowed_sectors')->nullable();
            $table->text('notes')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->index('organization_type');
            $table->index('status');
        });

        // 6. Anti-Duplication Verification Logs (سجل التحقق ومنع الازدواجية)
        Schema::create('dams_verification_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('donor_id')->nullable();
            $table->string('searched_identifier'); // national ID or block/parcel
            $table->string('query_type')->default('national_id'); // national_id, parcel_block, certificate_qr, map_view
            $table->boolean('result_found')->default(false);
            $table->integer('records_matched_count')->default(0);
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('donor_id');
            $table->index('searched_identifier');
            $table->index('query_type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dams_verification_logs');
        Schema::dropIfExists('dams_donor_organizations');
        Schema::dropIfExists('dams_media_attachments');
        Schema::dropIfExists('dams_audit_logs');
        Schema::dropIfExists('dams_damage_records');
        Schema::dropIfExists('dams_pricing_criteria');
    }
};
