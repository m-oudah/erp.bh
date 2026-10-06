<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Zones (الأحياء)
        Schema::create('bhm_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->timestamps();
        });

        // 2. Subzones (المناطق الفرعية)
        Schema::create('bhm_subzones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')->nullable()->constrained('bhm_zones')->nullOnDelete();
            $table->string('name', 200);
            $table->timestamps();
        });

        // 3. Street Types
        Schema::create('bhm_street_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->timestamps();
        });

        // 4. Streets (الشوارع)
        Schema::create('bhm_streets', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->foreignId('zone_id')->nullable()->constrained('bhm_zones')->nullOnDelete();
            $table->foreignId('subzone_id')->nullable()->constrained('bhm_subzones')->nullOnDelete();
            $table->timestamps();
        });

        // 5. Regions (المناطق التنظيمية)
        Schema::create('bhm_regions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->timestamps();
        });

        // 6. Municipalities (البلديات)
        Schema::create('bhm_municipalities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('code', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bhm_streets');
        Schema::dropIfExists('bhm_street_types');
        Schema::dropIfExists('bhm_subzones');
        Schema::dropIfExists('bhm_zones');
        Schema::dropIfExists('bhm_regions');
        Schema::dropIfExists('bhm_municipalities');
    }
};
