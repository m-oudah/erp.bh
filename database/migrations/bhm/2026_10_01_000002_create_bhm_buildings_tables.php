<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Building Types
        Schema::create('bhm_building_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->timestamps();
        });

        // Building Statuses
        Schema::create('bhm_building_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->timestamps();
        });

        // Building Property Types
        Schema::create('bhm_building_property_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->timestamps();
        });

        // Building Uses
        Schema::create('bhm_building_uses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->timestamps();
        });

        // Building Materials
        Schema::create('bhm_building_materials', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->timestamps();
        });

        // Building Finishes
        Schema::create('bhm_building_finishes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->timestamps();
        });

        // Supervisors
        Schema::create('bhm_supervisors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('phone', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Main Buildings Table
        Schema::create('bhm_buildings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('street_id')->nullable()->constrained('bhm_streets')->nullOnDelete();
            $table->foreignId('zone_id')->nullable()->constrained('bhm_zones')->nullOnDelete();
            $table->foreignId('subzone_id')->nullable()->constrained('bhm_subzones')->nullOnDelete();
            $table->foreignId('building_type_id')->nullable()->constrained('bhm_building_types')->nullOnDelete();
            $table->foreignId('building_status_id')->nullable()->constrained('bhm_building_statuses')->nullOnDelete();
            $table->foreignId('building_property_type_id')->nullable()->constrained('bhm_building_property_types')->nullOnDelete();
            $table->foreignId('supervisor_id')->nullable()->constrained('bhm_supervisors')->nullOnDelete();
            $table->string('file_number', 50)->nullable()->index();
            $table->string('building_number', 50)->nullable()->index();
            $table->string('building_name', 500)->nullable();
            $table->string('block_number', 20)->nullable();
            $table->string('parcel_number', 20)->nullable();
            $table->string('ownership_notes', 500)->nullable();
            $table->integer('construction_status')->unsigned()->nullable();
            // Usages (boolean flags)
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
            $table->string('building_usage_notes', 500)->nullable();
            $table->boolean('building_special_case')->nullable();
            // Wall materials (boolean flags)
            $table->boolean('wall_stone')->nullable();
            $table->boolean('wall_stone_concrete')->nullable();
            $table->boolean('wall_reinforced_concrete')->nullable();
            $table->boolean('wall_concrete_stone')->nullable();
            $table->boolean('wall_sand_stone')->nullable();
            $table->boolean('wall_other')->nullable();
            $table->string('wall_notes', 500)->nullable();
            // Roof materials (boolean flags)
            $table->boolean('last_floor_concrete')->nullable();
            $table->boolean('last_floor_asbast')->nullable();
            $table->boolean('last_floor_carmeed')->nullable();
            $table->boolean('last_floor_zenko')->nullable();
            $table->boolean('last_floor_other')->nullable();
            $table->string('last_floor_notes', 500)->nullable();
            // Status
            $table->tinyInteger('out_status')->nullable();
            $table->tinyInteger('overall_status')->nullable();
            $table->string('overall_status_notes', 500)->nullable();
            $table->date('building_date')->nullable();
            // Finish types (boolean flags)
            $table->boolean('finish_qesara')->nullable();
            $table->boolean('finish_italian')->nullable();
            $table->boolean('finish_tiles')->nullable();
            $table->boolean('finish_j_stone')->nullable();
            $table->boolean('finish_granuleet')->nullable();
            $table->boolean('finish_other')->nullable();
            $table->string('finish_notes', 500)->nullable();
            // Utilities
            $table->tinyInteger('water_source')->nullable();
            $table->tinyInteger('sewage')->nullable();
            $table->integer('electricity_source')->nullable();
            $table->smallInteger('elevators_count')->nullable();
            $table->smallInteger('escape_stairs')->nullable();
            // Misc flags
            $table->boolean('historical')->default(false);
            $table->boolean('adding_new_floor')->default(false);
            $table->boolean('abandond')->default(false);
            $table->string('session_date', 10)->nullable();
            $table->date('creation_date')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('collector_id')->nullable();
            $table->unsignedInteger('inspector_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Building Uses Pivot
        Schema::create('bhm_building_building_use', function (Blueprint $table) {
            $table->foreignId('building_id')->constrained('bhm_buildings')->cascadeOnDelete();
            $table->foreignId('building_use_id')->constrained('bhm_building_uses')->cascadeOnDelete();
            $table->primary(['building_id', 'building_use_id']);
        });

        // Building Materials Pivot
        Schema::create('bhm_building_building_material', function (Blueprint $table) {
            $table->foreignId('building_id')->constrained('bhm_buildings')->cascadeOnDelete();
            $table->foreignId('building_material_id')->constrained('bhm_building_materials')->cascadeOnDelete();
            $table->primary(['building_id', 'building_material_id']);
        });

        // Building Financial Data
        Schema::create('bhm_build_financial', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id')->constrained('bhm_buildings')->cascadeOnDelete();
            $table->double('land_area')->nullable();
            $table->double('dev_fees')->nullable();
            $table->double('dev_disc_val')->nullable();
            $table->double('dev_disc_rat')->nullable();
            $table->double('dev_per_meter')->nullable();
            $table->double('dev_remains')->nullable();
            $table->double('fees_remains')->nullable();
            $table->string('notes', 255)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bhm_build_financial');
        Schema::dropIfExists('bhm_building_building_material');
        Schema::dropIfExists('bhm_building_building_use');
        Schema::dropIfExists('bhm_buildings');
        Schema::dropIfExists('bhm_supervisors');
        Schema::dropIfExists('bhm_building_finishes');
        Schema::dropIfExists('bhm_building_materials');
        Schema::dropIfExists('bhm_building_uses');
        Schema::dropIfExists('bhm_building_property_types');
        Schema::dropIfExists('bhm_building_statuses');
        Schema::dropIfExists('bhm_building_types');
    }
};
