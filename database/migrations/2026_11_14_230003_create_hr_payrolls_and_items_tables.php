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
        Schema::create('hr_payrolls', function (Blueprint $table) {
            $table->id();
            $table->string('payroll_number', 50)->unique();
            $table->smallInteger('year');
            $table->tinyInteger('month');
            $table->string('title', 150);
            $table->enum('status', ['draft', 'approved', 'paid'])->default('draft');

            $table->unsignedInteger('total_employees')->default(0);
            $table->decimal('total_basic_salary', 12, 2)->default(0.00);
            $table->decimal('total_allowances', 12, 2)->default(0.00);
            $table->decimal('total_deductions', 12, 2)->default(0.00);
            $table->decimal('total_net_salary', 12, 2)->default(0.00);

            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('hr_payroll_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->constrained('hr_payrolls')->cascadeOnDelete();
            $table->foreignId('employee_id')->nullable()->constrained('hr_employees')->nullOnDelete();

            // بيانات مجمدة وقت التوليد
            $table->string('employee_number', 30)->nullable();
            $table->string('employee_name', 200);
            $table->string('job_title', 150)->nullable();
            $table->string('department_name', 150)->nullable();
            $table->string('contract_type', 50)->nullable();
            $table->string('grade_name', 100)->nullable();
            $table->unsignedSmallInteger('step')->default(0);
            $table->string('bank_name', 100)->nullable();
            $table->string('bank_account_number', 50)->nullable();
            $table->string('iban', 50)->nullable();

            // الاستحقاقات
            $table->decimal('base_salary', 10, 2)->default(0.00);
            $table->decimal('nature_of_work_allowance', 10, 2)->default(0.00);
            $table->decimal('spouse_allowance', 10, 2)->default(0.00);
            $table->decimal('children_allowance', 10, 2)->default(0.00);
            $table->decimal('qualification_allowance', 10, 2)->default(0.00);
            $table->decimal('position_allowance', 10, 2)->default(0.00);
            $table->decimal('cost_of_living_allowance', 10, 2)->default(0.00);
            $table->decimal('risk_allowance', 10, 2)->default(0.00);
            $table->decimal('overtime_allowance', 10, 2)->default(0.00);
            $table->decimal('bonus_allowance', 10, 2)->default(0.00);
            $table->decimal('other_allowances', 10, 2)->default(0.00);
            $table->decimal('total_allowances', 10, 2)->default(0.00);
            $table->decimal('gross_salary', 10, 2)->default(0.00);

            // الاستقطاعات
            $table->decimal('pension_deduction', 10, 2)->default(0.00);
            $table->decimal('health_insurance_deduction', 10, 2)->default(0.00);
            $table->decimal('loan_deduction', 10, 2)->default(0.00);
            $table->decimal('other_deductions', 10, 2)->default(0.00);
            $table->decimal('total_deductions', 10, 2)->default(0.00);

            // الصافي
            $table->decimal('net_salary', 10, 2)->default(0.00);
            $table->string('notes')->nullable();
            $table->boolean('is_manually_adjusted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_payroll_items');
        Schema::dropIfExists('hr_payrolls');
    }
};

