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
        Schema::table('departments', function (Blueprint $table) {
            if (!Schema::hasColumn('departments', 'parent_id')) {
                $table->foreignId('parent_id')->nullable()->after('id')->constrained('departments')->nullOnDelete();
            }
            if (!Schema::hasColumn('departments', 'type')) {
                $table->string('type', 50)->default('department')->after('name');
            }
            if (!Schema::hasColumn('departments', 'manager_title')) {
                $table->string('manager_title', 100)->nullable()->after('manager_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            if (Schema::hasColumn('departments', 'parent_id')) {
                $table->dropForeign(['parent_id']);
                $table->dropColumn('parent_id');
            }
            if (Schema::hasColumn('departments', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('departments', 'manager_title')) {
                $table->dropColumn('manager_title');
            }
        });
    }
};

