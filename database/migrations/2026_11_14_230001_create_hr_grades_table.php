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
        Schema::create('hr_grades', function (Blueprint $table) {
            $table->id();
            $table->string('category', 50); // العليا, الأولى, الثانية, الثالثة, الرابعة, الخامسة
            $table->string('grade_code', 20)->unique(); // A1, A2, A3, 1, 2, 3, ...
            $table->string('grade_name', 100); // الدرجة الخاصة A1, الدرجة الأولى, ...
            $table->decimal('base_salary', 10, 2)->default(0.00); // بداية مربوط الدرجة
            $table->decimal('annual_raise', 10, 2)->default(0.00); // قيمة العلاوة الدورية السنوية (المربوط)
            $table->unsignedInteger('max_steps')->default(20); // أقصى عدد لسنوات المربوط
            $table->string('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_grades');
    }
};

