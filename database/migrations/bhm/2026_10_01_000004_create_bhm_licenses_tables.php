<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // License Form Replies
        Schema::create('bhm_license_form_replies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('color', 30)->nullable();
            $table->timestamps();
        });

        // License Forms
        Schema::create('bhm_license_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id')->nullable()->constrained('bhm_buildings')->nullOnDelete();
            $table->string('first_name', 100)->nullable();
            $table->string('second_name', 100)->nullable();
            $table->string('third_name', 100)->nullable();
            $table->string('sur_name', 100)->nullable();
            $table->string('id_card', 20)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('building_number', 50)->nullable();
            $table->text('subject')->nullable();
            $table->tinyInteger('status')->nullable();
            // Department opinions
            $table->foreignId('legal_opinion')->nullable()->constrained('bhm_license_form_replies')->nullOnDelete();
            $table->foreignId('area_opinion')->nullable()->constrained('bhm_license_form_replies')->nullOnDelete();
            $table->foreignId('plan_opinion')->nullable()->constrained('bhm_license_form_replies')->nullOnDelete();
            $table->foreignId('water_opinion')->nullable()->constrained('bhm_license_form_replies')->nullOnDelete();
            $table->foreignId('sewer_opinion')->nullable()->constrained('bhm_license_form_replies')->nullOnDelete();
            $table->foreignId('collection_opinion')->nullable()->constrained('bhm_license_form_replies')->nullOnDelete();
            $table->foreignId('gis_opinion')->nullable()->constrained('bhm_license_form_replies')->nullOnDelete();
            // Attachment IDs
            $table->unsignedBigInteger('title_deed_id')->nullable();
            $table->unsignedBigInteger('general_site_plan_id')->nullable();
            $table->unsignedBigInteger('construction_map_id')->nullable();
            $table->unsignedBigInteger('undertaking_supervise_id')->nullable();
            $table->unsignedBigInteger('aprobaciones_terceros_id')->nullable();
            $table->unsignedBigInteger('attachment_one_id')->nullable();
            $table->timestamps();
        });

        // Attachments
        Schema::create('bhm_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id')->nullable()->constrained('bhm_buildings')->nullOnDelete();
            $table->foreignId('license_form_id')->nullable()->constrained('bhm_license_forms')->nullOnDelete();
            $table->unsignedBigInteger('proof_of_case_id')->nullable();
            $table->string('path', 500)->nullable();
            $table->string('file_name', 255)->nullable();
            $table->string('file_type', 50)->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
        });

        // Category Archive Attachments
        Schema::create('bhm_category_archive_attachments', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->timestamps();
        });

        // Regulatory Disclosure Reports
        Schema::create('bhm_regulatory_disclosure_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id')->nullable()->constrained('bhm_buildings')->nullOnDelete();
            $table->foreignId('license_form_id')->nullable()->constrained('bhm_license_forms')->nullOnDelete();
            $table->boolean('isproperty')->nullable();
            $table->boolean('isorted')->nullable();
            $table->tinyInteger('region')->nullable();
            $table->tinyInteger('location_status')->nullable();
            $table->string('total_coupon_space', 255)->nullable();
            $table->string('building_area', 255)->nullable();
            $table->string('rebounds_front', 255)->nullable();
            $table->string('rebounds_back', 255)->nullable();
            $table->string('rebounds_right', 255)->nullable();
            $table->string('rebounds_left', 255)->nullable();
            $table->string('construction_ratio', 255)->nullable();
            $table->string('number_floor', 255)->nullable();
            $table->text('purpose_building_use')->nullable();
            $table->text('site_on_structural')->nullable();
            $table->text('passes_through_site')->nullable();
            $table->text('territory_regulatory_requirement')->nullable();
            $table->text('department_notes')->nullable();
            $table->boolean('trust')->nullable();
            $table->string('development_area', 255)->nullable();
            $table->timestamps();
        });

        // Proof of Cases
        Schema::create('bhm_proof_of_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id')->nullable()->constrained('bhm_buildings')->nullOnDelete();
            $table->string('case_number', 100)->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->boolean('is_confirmed')->default(false);
            $table->unsignedInteger('confirmed_by')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bhm_proof_of_cases');
        Schema::dropIfExists('bhm_regulatory_disclosure_reports');
        Schema::dropIfExists('bhm_category_archive_attachments');
        Schema::dropIfExists('bhm_attachments');
        Schema::dropIfExists('bhm_license_forms');
        Schema::dropIfExists('bhm_license_form_replies');
    }
};
