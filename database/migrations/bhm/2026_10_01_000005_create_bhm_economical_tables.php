<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Economical Sectors
        Schema::create('bhm_economical_sectors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->timestamps();
        });

        // Craft Categories
        Schema::create('bhm_craft_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->timestamps();
        });

        // Craft Types
        Schema::create('bhm_craft_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable();
            $table->foreignId('category_id')->nullable()->constrained('bhm_craft_categories')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // Craft Status
        Schema::create('bhm_craft_status', function (Blueprint $table) {
            $table->id();
            $table->string('description', 255);
            $table->timestamps();
        });

        // Professions Categories
        Schema::create('bhm_professions_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->timestamps();
        });

        // Professions
        Schema::create('bhm_professions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->foreignId('category_id')->nullable()->constrained('bhm_professions_categories')->nullOnDelete();
            $table->timestamps();
        });

        // Currencies
        Schema::create('bhm_currencies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 10)->nullable();
            $table->timestamps();
        });

        // Economical (النشاط الاقتصادي المرتبط بالمباني)
        Schema::create('bhm_economical', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id')->nullable()->constrained('bhm_buildings')->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('bhm_units')->nullOnDelete();
            $table->foreignId('building_owner_id')->nullable()->constrained('bhm_building_owners')->nullOnDelete();
            $table->foreignId('job_sector_id')->nullable()->constrained('bhm_economical_sectors')->nullOnDelete();
            $table->string('id_card', 20)->nullable();
            $table->string('name', 300)->nullable();
            $table->string('trade_name', 300)->nullable();
            $table->boolean('isLicensed')->nullable();
            $table->boolean('isDanger')->nullable();
            $table->string('notes', 500)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
        });

        // Economical Owners
        Schema::create('bhm_economical_owners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('economical_id')->constrained('bhm_economical')->cascadeOnDelete();
            $table->string('id_card', 20)->nullable();
            $table->string('first_name', 100)->nullable();
            $table->string('second_name', 100)->nullable();
            $table->string('third_name', 100)->nullable();
            $table->string('sur_name', 100)->nullable();
            $table->string('phone', 30)->nullable();
            $table->timestamps();
        });

        // Craft Attachments
        Schema::create('bhm_craft_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('economical_id')->nullable()->constrained('bhm_economical')->nullOnDelete();
            $table->string('path', 500);
            $table->string('file_name', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bhm_craft_attachments');
        Schema::dropIfExists('bhm_economical_owners');
        Schema::dropIfExists('bhm_economical');
        Schema::dropIfExists('bhm_currencies');
        Schema::dropIfExists('bhm_professions');
        Schema::dropIfExists('bhm_professions_categories');
        Schema::dropIfExists('bhm_craft_status');
        Schema::dropIfExists('bhm_craft_types');
        Schema::dropIfExists('bhm_craft_categories');
        Schema::dropIfExists('bhm_economical_sectors');
    }
};
