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
        Schema::create('archive_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('archive_file_id')->constrained('archive_files')->onDelete('cascade'); // ارتباط بالملف المادي
            $table->string('document_no')->nullable(); // رقم الوثيقة/الكتاب
            $table->string('document_name'); // اسم/عنوان الوثيقة
            $table->string('document_type')->nullable(); // نوع الوثيقة (pdf, doc, etc)
            $table->string('document_path'); // مسار الملف
            $table->foreignId('added_by')->nullable()->constrained('users')->onDelete('set null'); // الموظف الذي قام بالإضافة
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archive_documents');
    }
};
