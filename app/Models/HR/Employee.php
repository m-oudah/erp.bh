<?php

namespace App\Models\HR;

use App\Models\Correspondence\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hr_employees';

    protected $fillable = [
        'employee_number',
        'national_id',
        'first_name',
        'second_name',
        'third_name',
        'last_name',
        'full_name',
        'gender',
        'date_of_birth',
        'marital_status',
        'dependents_count',
        'has_spouse_allowance',
        'mobile',
        'phone',
        'email',
        'address',
        'department_id',
        'job_title',
        'contract_type',
        'hire_date',
        'end_of_contract_date',
        'status',
        'direct_manager_id',
        'user_id',
        'grade_id',
        'current_step',
        'custom_base_salary',
        'nature_of_work_rate',
        'qualification',
        'qualification_allowance',
        'position_allowance',
        'cost_of_living_allowance',
        'risk_allowance',
        'other_allowances',
        'is_pension_subscribed',
        'pension_rate',
        'health_insurance_fixed',
        'loan_deduction',
        'other_deductions',
        'bank_name',
        'bank_branch',
        'bank_account_number',
        'iban',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'hire_date' => 'date',
        'end_of_contract_date' => 'date',
        'dependents_count' => 'integer',
        'has_spouse_allowance' => 'boolean',
        'current_step' => 'integer',
        'custom_base_salary' => 'decimal:2',
        'nature_of_work_rate' => 'decimal:2',
        'qualification_allowance' => 'decimal:2',
        'position_allowance' => 'decimal:2',
        'cost_of_living_allowance' => 'decimal:2',
        'risk_allowance' => 'decimal:2',
        'other_allowances' => 'decimal:2',
        'is_pension_subscribed' => 'boolean',
        'pension_rate' => 'decimal:2',
        'health_insurance_fixed' => 'decimal:2',
        'loan_deduction' => 'decimal:2',
        'other_deductions' => 'decimal:2',
    ];

    public const CONTRACT_TYPES = [
        'permanent' => 'تثبيت دائم',
        'temporary_contract' => 'عقد مؤقت',
        'daily' => 'مياومة / يومي',
        'unemployment' => 'بطالة تشغيل مؤقت',
        'delegation' => 'انتداب',
    ];

    public const STATUSES = [
        'active' => 'على رأس عمله',
        'suspended' => 'موقوف مؤقتاً',
        'terminated' => 'منتهية خدماته / مستقيل',
    ];

    public const QUALIFICATIONS = [
        'secondary' => 'ثانوية عامة فما دون',
        'diploma' => 'دبلوم متوسط سنتين',
        'bachelor' => 'بكالوريوس',
        'higher_diploma' => 'دبلوم عالي',
        'master' => 'ماجستير',
        'phd' => 'دكتوراه',
        'other' => 'أخرى',
    ];

    public const MARITAL_STATUSES = [
        'single' => 'أعزب / عزباء',
        'married' => 'متزوج / متزوجة',
        'divorced' => 'مطلق / مطلقة',
        'widowed' => 'أرمل / أرملة',
    ];

    // Relations
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    public function directManager()
    {
        return $this->belongsTo(Employee::class, 'direct_manager_id');
    }

    public function subordinates()
    {
        return $this->hasMany(Employee::class, 'direct_manager_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function payrollItems()
    {
        return $this->hasMany(PayrollItem::class, 'employee_id');
    }

    // Accessors
    public function getContractTypeArabicAttribute(): string
    {
        return self::CONTRACT_TYPES[$this->contract_type] ?? $this->contract_type;
    }

    public function getStatusArabicAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getQualificationArabicAttribute(): string
    {
        return self::QUALIFICATIONS[$this->qualification] ?? ($this->qualification ?? 'غير محدد');
    }

    public function getMaritalStatusArabicAttribute(): string
    {
        return self::MARITAL_STATUSES[$this->marital_status] ?? $this->marital_status;
    }

    /**
     * Compute current basic salary (from grade + step, or custom override)
     */
    public function calculateBasicSalary(): float
    {
        if ($this->custom_base_salary !== null && (float) $this->custom_base_salary > 0) {
            return (float) $this->custom_base_salary;
        }

        if ($this->grade) {
            return $this->grade->calculateSalaryForStep($this->current_step ?? 0);
        }

        return 0.00;
    }

    /**
     * Calculate all salary components according to Palestinian Civil Service rules
     */
    public function calculateSalaryComponents(float $overtime = 0.00, float $bonus = 0.00): array
    {
        $baseSalary = round($this->calculateBasicSalary(), 2);

        // 1. علاوة طبيعة العمل (% من الراتب الأساسي)
        $natureRate = (float) ($this->nature_of_work_rate ?? 0.00);
        $natureOfWorkAllowance = round($baseSalary * ($natureRate / 100), 2);

        // 2. العلاوة الاجتماعية (الزوجة والأولاد)
        // علاوة الزوجة المعتمدة 60 شيكل، علاوة الابن 20 شيكل
        $spouseAllowance = $this->has_spouse_allowance ? 60.00 : 0.00;
        $childrenAllowance = round(($this->dependents_count ?? 0) * 20.00, 2);

        // 3. علاوة المؤهل العلمي
        $qualificationAllowance = round((float) ($this->qualification_allowance ?? 0.00), 2);

        // 4. علاوة المنصب / المسؤولية الإدارية
        $positionAllowance = round((float) ($this->position_allowance ?? 0.00), 2);

        // 5. غلاء المعيشة والمخاطرة
        $costOfLivingAllowance = round((float) ($this->cost_of_living_allowance ?? 0.00), 2);
        $riskAllowance = round((float) ($this->risk_allowance ?? 0.00), 2);

        // 6. علاوات أخرى وإضافي ومكافآت
        $otherAllowances = round((float) ($this->other_allowances ?? 0.00), 2);
        $overtimeAllowance = round($overtime, 2);
        $bonusAllowance = round($bonus, 2);

        $totalAllowances = round(
            $natureOfWorkAllowance +
            $spouseAllowance +
            $childrenAllowance +
            $qualificationAllowance +
            $positionAllowance +
            $costOfLivingAllowance +
            $riskAllowance +
            $otherAllowances +
            $overtimeAllowance +
            $bonusAllowance,
            2
        );

        $grossSalary = round($baseSalary + $totalAllowances, 2);

        // الاستقطاعات
        // 1. هيئة التقاعد والمعاشات: 10% من (الراتب الأساسي + علاوة طبيعة العمل)
        $pensionDeduction = 0.00;
        if ($this->is_pension_subscribed && $this->contract_type === 'permanent') {
            $pensionBase = $baseSalary + $natureOfWorkAllowance;
            $pensionRate = (float) ($this->pension_rate ?? 10.00);
            $pensionDeduction = round($pensionBase * ($pensionRate / 100), 2);
        }

        // 2. التأمين الصحي
        $healthInsuranceDeduction = round((float) ($this->health_insurance_fixed ?? 0.00), 2);

        // 3. أقساط السلف والديون البلدية
        $loanDeduction = round((float) ($this->loan_deduction ?? 0.00), 2);

        // 4. استقطاعات أخرى
        $otherDeductions = round((float) ($this->other_deductions ?? 0.00), 2);

        $totalDeductions = round(
            $pensionDeduction +
            $healthInsuranceDeduction +
            $loanDeduction +
            $otherDeductions,
            2
        );

        $netSalary = max(0.00, round($grossSalary - $totalDeductions, 2));

        return [
            'base_salary' => $baseSalary,
            'nature_of_work_allowance' => $natureOfWorkAllowance,
            'spouse_allowance' => $spouseAllowance,
            'children_allowance' => $childrenAllowance,
            'qualification_allowance' => $qualificationAllowance,
            'position_allowance' => $positionAllowance,
            'cost_of_living_allowance' => $costOfLivingAllowance,
            'risk_allowance' => $riskAllowance,
            'overtime_allowance' => $overtimeAllowance,
            'bonus_allowance' => $bonusAllowance,
            'other_allowances' => $otherAllowances,
            'total_allowances' => $totalAllowances,
            'gross_salary' => $grossSalary,
            'pension_deduction' => $pensionDeduction,
            'health_insurance_deduction' => $healthInsuranceDeduction,
            'loan_deduction' => $loanDeduction,
            'other_deductions' => $otherDeductions,
            'total_deductions' => $totalDeductions,
            'net_salary' => $netSalary,
        ];
    }
}

