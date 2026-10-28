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
        Schema::table('internal_memos', function (Blueprint $table) {
            if (!Schema::hasColumn('internal_memos', 'memo_type')) {
                $table->string('memo_type')->default('مذكرة داخلية')->after('subject');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internal_memos', function (Blueprint $table) {
            if (Schema::hasColumn('internal_memos', 'memo_type')) {
                $table->dropColumn('memo_type');
            }
        });
    }
};
