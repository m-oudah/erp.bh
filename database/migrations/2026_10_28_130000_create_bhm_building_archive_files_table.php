<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bhm_building_archive_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id')->constrained('bhm_buildings')->cascadeOnDelete();
            $table->foreignId('archive_file_id')->constrained('archive_files')->cascadeOnDelete();
            $table->boolean('is_primary')->default(false); // Indicates the main planning/organizing file
            $table->timestamps();

            $table->unique(['building_id', 'archive_file_id'], 'bhm_bld_arch_bld_file_unique');
            $table->index(['building_id', 'is_primary']);
        });

        // Seed existing links based on bhm_buildings.file_number
        // Find buildings where file_number is valid
        $buildings = DB::table('bhm_buildings')
            ->whereNotNull('file_number')
            ->whereNotIn('file_number', ['', '0', 'لا يوجد ملف'])
            ->select('id', 'file_number')
            ->get();

        foreach ($buildings as $building) {
            $raw = trim($building->file_number);
            $normalized = str_replace(['أ', 'إ', 'آ'], 'ا', $raw);

            $matchedFiles = DB::table('archive_files')
                ->where('file_no', $raw)
                ->orWhere('file_no', $normalized)
                ->orWhere('file_no', ' ' . $raw)
                ->select('id', 'archive_file_type_id')
                ->get();

            $hasPrimary = false;
            foreach ($matchedFiles as $f) {
                $isPrimary = (!$hasPrimary && $f->archive_file_type_id == 1);
                if ($isPrimary) {
                    $hasPrimary = true;
                }

                DB::table('bhm_building_archive_files')->insertOrIgnore([
                    'building_id' => $building->id,
                    'archive_file_id' => $f->id,
                    'is_primary' => $isPrimary,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bhm_building_archive_files');
    }
};
