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
        Schema::create('memo_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memo_id')->constrained('internal_memos')->cascadeOnDelete();
            $table->foreignId('from_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('to_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('to_department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('action_type'); // إنشاء, تحويل مع توجيه, إضافة مرفق, طلب مراجعة, إنجاز وإغلاق, إعادة فتح
            $table->text('directive_text')->nullable(); // الشروحات والتوجيهات الإدارية
            $table->timestamps();

            $table->index(['memo_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memo_movements');
    }
};
