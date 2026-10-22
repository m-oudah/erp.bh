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
        Schema::create('internal_memos', function (Blueprint $table) {
            $table->id();
            $table->string('memo_number')->unique();
            $table->string('subject');
            $table->longText('content');
            $table->string('priority')->default('عادية'); // عادية, متوسطة, عاجلة, سري / عاجل جداً
            $table->string('status')->default('قيد المعالجة'); // مسودة, قيد المعالجة, بانتظار رد, تم الإنجاز, ملغاة
            
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('current_assignee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('target_department_id')->nullable()->constrained('departments')->nullOnDelete();
            
            $table->foreignId('linked_archive_file_id')->nullable()->constrained('archive_files')->nullOnDelete();
            $table->foreignId('auto_archive_file_id')->nullable()->constrained('archive_files')->nullOnDelete();
            
            $table->date('due_date')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->foreignId('closed_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for fast searching & filtering
            $table->index(['status', 'priority']);
            $table->index('created_by');
            $table->index('current_assignee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internal_memos');
    }
};
