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
        Schema::create('bhm_craft_type_economical', function (Blueprint $table) {
            $table->id();
            $table->foreignId('economical_id')->constrained('bhm_economical')->cascadeOnDelete();
            $table->foreignId('craft_type_id')->nullable()->constrained('bhm_craft_types')->nullOnDelete();
            $table->foreignId('craft_category_id')->nullable()->constrained('bhm_craft_categories')->nullOnDelete();
            $table->foreignId('craft_status_id')->nullable()->constrained('bhm_craft_status')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bhm_craft_type_economical');
    }
};
