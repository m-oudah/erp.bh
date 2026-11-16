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
        Schema::create('hr_employees', function (Blueprint $table) {
            $table->id();

            // بيانات الهوية الشخصية
            $table->string('employee_number', 30)->unique();
            $table->string('national_id', 9)->unique()->index();
            $table->string('first_name', 50);
            $table->string('second_name', 50)->nullable();
            $table->string('third_name', 50)->nullable();
            $table->string('last_name', 50);
            $table->string('full_name', 200)->index();
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->date('date_of_birth')->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->default('single');
            $table->unsignedTinyInteger('dependents_count')->default(0); // عدد الأبناء المستحقين للعلاوة
            $table->boolean('has_spouse_allowance')->default(false); // استحقاق علاوة الزوجة

            // الاتصال والعنوان
            $table->string('mobile', 30)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('address', 255)->nullable();

            // الوظيفة والهيكلية الإدارية
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('job_title', 150);
            $table->enum('contract_type', ['permanent', 'temporary_contract', 'daily', 'unemployment', 'delegation'])->default('permanent');
            $table->date('hire_date')->nullable();
            $table->date('end_of_contract_date')->nullable();
            $table->string('status', 30)->default('active'); // active, suspended, terminated
            $table->foreignId('direct_manager_id')->nullable()->constrained('hr_employees')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // سلم الرواتب والخدمة المدنية
            $table->foreignId('grade_id')->nullable()->constrained('hr_grades')->nullOnDelete();
            $table->unsignedSmallInteger('current_step')->default(0); // سنوات المربوط / العلاوات الدورية
            $table->decimal('custom_base_salary', 10, 2)->nullable(); // راتب أساسي مقطوع للمياومة أو العقود الخاصة

            // العلاوات المعتمدة تلقائياً
            $table->decimal('nature_of_work_rate', 5, 2)->default(0.00); // نسبة علاوة طبيعة العمل من الراتب الأساسي
            $table->string('qualification', 50)->nullable(); // secondary, diploma, bachelor, master, phd, other
            $table->decimal('qualification_allowance', 10, 2)->default(0.00); // علاوة المؤهل العلمي
            $table->decimal('position_allowance', 10, 2)->default(0.00); // علاوة المنصب / المسؤولية الإدارية
            $table->decimal('cost_of_living_allowance', 10, 2)->default(0.00); // علاوة غلاء المعيشة
            $table->decimal('risk_allowance', 10, 2)->default(0.00); // علاوة المخاطرة
            $table->decimal('other_allowances', 10, 2)->default(0.00); // علاوات ثابتة أخرى

            // الاستقطاعات الثابتة
            $table->boolean('is_pension_subscribed')->default(true); // خاضع للتقاعد والمعاشات
            $table->decimal('pension_rate', 5, 2)->default(10.00); // نسبة استقطاع التقاعد (10%)
            $table->decimal('health_insurance_fixed', 10, 2)->default(0.00); // استقطاع التأمين الصحي الثابت
            $table->decimal('loan_deduction', 10, 2)->default(0.00); // قسط سلفة أو قرض بلدي
            $table->decimal('other_deductions', 10, 2)->default(0.00); // استقطاعات ثابتة أخرى

            // الحساب البنكي
            $table->string('bank_name', 100)->nullable();
            $table->string('bank_branch', 100)->nullable();
            $table->string('bank_account_number', 50)->nullable();
            $table->string('iban', 50)->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_employees');
    }
};

