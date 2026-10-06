<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Building Owners
        Schema::create('bhm_building_owners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id')->nullable()->constrained('bhm_buildings')->nullOnDelete();
            $table->string('id_card', 20)->nullable()->index();
            $table->string('first_name', 100)->nullable();
            $table->string('second_name', 100)->nullable();
            $table->string('third_name', 100)->nullable();
            $table->string('sur_name', 100)->nullable();
            $table->string('phone_number', 30)->nullable();
            $table->boolean('is_approve')->nullable();
            $table->string('building_number', 50)->nullable();
            $table->unsignedBigInteger('license_form_id')->nullable();
            $table->timestamps();
        });

        // Previous Owners
        Schema::create('bhm_previous_owners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id')->nullable()->constrained('bhm_buildings')->nullOnDelete();
            $table->string('id_card', 20)->nullable();
            $table->string('first_name', 100)->nullable();
            $table->string('second_name', 100)->nullable();
            $table->string('third_name', 100)->nullable();
            $table->string('sur_name', 100)->nullable();
            $table->string('phone_number', 30)->nullable();
            $table->timestamps();
        });

        // Floors Descriptions
        Schema::create('bhm_floors_descriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id')->nullable()->constrained('bhm_buildings')->nullOnDelete();
            $table->integer('floor_number')->nullable();
            $table->unsignedInteger('stores')->nullable();
            $table->unsignedInteger('departments')->nullable();
            $table->unsignedInteger('others')->nullable();
            $table->unsignedInteger('total_count')->nullable();
            $table->unsignedInteger('finish_full')->nullable();
            $table->unsignedInteger('finish_partial')->nullable();
            $table->unsignedInteger('finish_none')->nullable();
            $table->unsignedInteger('used')->nullable();
            $table->unsignedInteger('not_used')->nullable();
            $table->string('notes', 500)->nullable();
            $table->boolean('has_cantileaver')->default(false);
            $table->tinyInteger('is_licensed')->unsigned()->nullable();
            $table->double('area')->unsigned()->nullable();
            $table->double('lic_fees')->nullable();
            $table->double('lic_fees_discount')->nullable();
            $table->double('lic_fees_disc_val')->nullable();
            $table->double('lic_per_meter')->nullable();
            $table->boolean('got_license')->nullable();
            $table->string('lic_number', 10)->nullable();
            $table->decimal('licensed_area', 8, 2)->nullable();
            $table->decimal('license_fees', 8, 2)->unsigned()->nullable();
            $table->decimal('required_pay', 8, 2)->nullable();
            $table->decimal('area_before', 8, 2)->nullable();
            $table->string('building_number', 255)->nullable();
            $table->unsignedBigInteger('license_form_id')->nullable();
            $table->unsignedBigInteger('payment_number')->nullable();
            $table->timestamps();
        });

        // Units
        Schema::create('bhm_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id')->constrained('bhm_buildings')->cascadeOnDelete();
            $table->foreignId('floor_id')->nullable()->constrained('bhm_floors_descriptions')->nullOnDelete();
            $table->foreignId('building_owner_id')->nullable()->constrained('bhm_building_owners')->nullOnDelete();
            $table->unsignedInteger('street_number')->nullable();
            $table->string('building_number', 7)->nullable();
            $table->tinyInteger('floor_number')->nullable();
            $table->string('unit_number', 5)->nullable();
            $table->string('unit_name', 500)->nullable();
            $table->tinyInteger('unit_type')->nullable(); // 1=سكن, 2=تجاري
            $table->unsignedInteger('form_number')->nullable();
            $table->tinyInteger('ownership_type')->unsigned()->nullable();
            $table->string('ownership_notes', 500)->nullable();
            $table->tinyInteger('current_situation')->unsigned()->nullable();
            $table->string('current_situation_notes', 500)->nullable();
            // Usages
            $table->boolean('residential')->nullable();
            $table->boolean('commercial')->nullable();
            $table->boolean('industrial')->nullable();
            $table->boolean('educational')->nullable();
            $table->boolean('cultural')->nullable();
            $table->boolean('health')->nullable();
            $table->boolean('tourism')->nullable();
            $table->boolean('religous')->nullable();
            $table->boolean('institutions')->nullable();
            $table->boolean('other_usage')->nullable();
            $table->string('unit_usage_notes', 500)->nullable();
            // Status
            $table->tinyInteger('internal_status')->unsigned()->nullable();
            $table->tinyInteger('external_status')->unsigned()->default(0);
            $table->unsignedInteger('profession_type')->nullable();
            $table->string('profession_name', 500)->nullable();
            $table->tinyInteger('is_working')->unsigned()->nullable();
            $table->tinyInteger('has_banner')->unsigned()->nullable();
            $table->tinyInteger('is_lighting_banner')->unsigned()->nullable();
            $table->string('system_number', 20)->nullable();
            $table->string('water_notes', 500)->nullable();
            $table->string('unit_notes', 500)->nullable();
            $table->unsignedInteger('collector_id')->nullable();
            $table->unsignedInteger('inspector_id')->nullable();
            $table->unsignedInteger('supervisor_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->date('creation_date')->nullable();
            $table->timestamps();
        });

        // Building Owner Unit (pivot)
        Schema::create('bhm_building_owner_unit', function (Blueprint $table) {
            $table->foreignId('building_owner_id')->constrained('bhm_building_owners')->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained('bhm_units')->cascadeOnDelete();
            $table->primary(['building_owner_id', 'unit_id']);
        });

        // Unit Users
        Schema::create('bhm_unit_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('bhm_units')->cascadeOnDelete();
            $table->string('name', 200)->nullable();
            $table->string('id_no', 20)->nullable();
            $table->string('mobile', 30)->nullable();
            $table->timestamps();
        });

        // Development Data
        Schema::create('bhm_development_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('floor_description_id')->nullable()->constrained('bhm_floors_descriptions')->nullOnDelete();
            $table->string('notes', 500)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bhm_development_data');
        Schema::dropIfExists('bhm_unit_users');
        Schema::dropIfExists('bhm_building_owner_unit');
        Schema::dropIfExists('bhm_units');
        Schema::dropIfExists('bhm_floors_descriptions');
        Schema::dropIfExists('bhm_previous_owners');
        Schema::dropIfExists('bhm_building_owners');
    }
};
