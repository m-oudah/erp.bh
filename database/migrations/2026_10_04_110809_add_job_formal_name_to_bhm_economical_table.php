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
        Schema::table('bhm_economical', function (Blueprint $table) {
            $table->string('job_formal_name', 300)->nullable()->after('trade_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bhm_economical', function (Blueprint $table) {
            $table->dropColumn('job_formal_name');
        });
    }
};
