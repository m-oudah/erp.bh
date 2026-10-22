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
        Schema::create('internal_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memo_id')->nullable()->constrained('internal_memos')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_to')->constrained('users')->cascadeOnDelete();
            
            $table->date('due_date')->nullable();
            $table->string('priority')->default('عادية'); // عادية, متوسطة, عاجلة
            $table->string('status')->default('جديدة'); // جديدة, قيد التنفيذ, بانتظار الرد, تم الإنجاز, معلقة
            $table->timestamp('completed_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['status', 'priority']);
            $table->index('assigned_to');
            $table->index('created_by');
            $table->index('memo_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internal_tasks');
    }
};
