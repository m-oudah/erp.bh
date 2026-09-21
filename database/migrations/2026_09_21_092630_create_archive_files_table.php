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
        Schema::create('archive_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('archive_file_type_id')->nullable()->constrained('archive_file_types')->onDelete('set null'); // نوع الملف
            $table->string('file_no')->unique(); // رقم الملف
            $table->string('file_name'); // اسم الملف/المواطن
            $table->json('dynamic_data')->nullable(); // القيم الديناميكية
            $table->foreignId('department_id')->nullable()->constrained('users'); // For now constrain to users or drop constrain if departments table doesn't exist yet
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archive_files');
    }
};
