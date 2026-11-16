<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollItem extends Model
{
    use HasFactory;

    protected $table = 'hr_payroll_items';

    protected $fillable = [
        'payroll_id',
        'employee_id',
        'employee_number',
        'employee_name',
        'job_title',
        'department_name',
        'contract_type',
        'grade_name',
        'step',
        'bank_name',
        'bank_account_number',
        'iban',
        'base_salary',
        'nature_of_work_allowance',
        'spouse_allowance',
        'children_allowance',
        'qualification_allowance',
        'position_allowance',
        'cost_of_living_allowance',
        'risk_allowance',
        'overtime_allowance',
        'bonus_allowance',
        'other_allowances',
        'total_allowances',
        'gross_salary',
        'pension_deduction',
        'health_insurance_deduction',
        'loan_deduction',
        'other_deductions',
        'total_deductions',
        'net_salary',
        'notes',
        'is_manually_adjusted',
    ];

    protected $casts = [
        'step' => 'integer',
        'base_salary' => 'decimal:2',
        'nature_of_work_allowance' => 'decimal:2',
        'spouse_allowance' => 'decimal:2',
        'children_allowance' => 'decimal:2',
        'qualification_allowance' => 'decimal:2',
        'position_allowance' => 'decimal:2',
        'cost_of_living_allowance' => 'decimal:2',
        'risk_allowance' => 'decimal:2',
        'overtime_allowance' => 'decimal:2',
        'bonus_allowance' => 'decimal:2',
        'other_allowances' => 'decimal:2',
        'total_allowances' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'pension_deduction' => 'decimal:2',
        'health_insurance_deduction' => 'decimal:2',
        'loan_deduction' => 'decimal:2',
        'other_deductions' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'is_manually_adjusted' => 'boolean',
    ];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class, 'payroll_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     * Recalculate totals and net salary for this item
     */
    public function recalculateItemTotals(): void
    {
        $this->total_allowances = round(
            (float)$this->nature_of_work_allowance +
            (float)$this->spouse_allowance +
            (float)$this->children_allowance +
            (float)$this->qualification_allowance +
            (float)$this->position_allowance +
            (float)$this->cost_of_living_allowance +
            (float)$this->risk_allowance +
            (float)$this->overtime_allowance +
            (float)$this->bonus_allowance +
            (float)$this->other_allowances,
            2
        );

        $this->gross_salary = round((float)$this->base_salary + (float)$this->total_allowances, 2);

        $this->total_deductions = round(
            (float)$this->pension_deduction +
            (float)$this->health_insurance_deduction +
            (float)$this->loan_deduction +
            (float)$this->other_deductions,
            2
        );

        $this->net_salary = max(0.00, round((float)$this->gross_salary - (float)$this->total_deductions, 2));
    }
}

