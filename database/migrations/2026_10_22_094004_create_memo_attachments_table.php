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
        Schema::create('memo_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memo_id')->constrained('internal_memos')->cascadeOnDelete();
            $table->foreignId('movement_id')->nullable()->constrained('memo_movements')->nullOnDelete();
            $table->string('file_name');
            $table->string('file_path');
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('archive_document_id')->nullable()->constrained('archive_documents')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('memo_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memo_attachments');
    }
};
