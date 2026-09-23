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
        $tables = [
            'users',
            'archive_file_types',
            'archive_file_type_fields',
            'archive_document_categories',
            'archive_files',
            'archive_documents',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $tableBlueprint) {
                $tableBlueprint->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'users',
            'archive_file_types',
            'archive_file_type_fields',
            'archive_document_categories',
            'archive_files',
            'archive_documents',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $tableBlueprint) {
                $tableBlueprint->dropSoftDeletes();
            });
        }
    }
};
