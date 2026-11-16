<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $table = 'hr_grades';

    protected $fillable = [
        'category',
        'grade_code',
        'grade_name',
        'base_salary',
        'annual_raise',
        'max_steps',
        'notes',
        'is_active',
        'order',
    ];

    protected $casts = [
        'base_salary' => 'decimal:2',
        'annual_raise' => 'decimal:2',
        'max_steps' => 'integer',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class, 'grade_id');
    }

    /**
     * Calculate basic salary based on step (مربوط الدرجة)
     */
    public function calculateSalaryForStep(int $step = 0): float
    {
        $step = max(0, min($step, $this->max_steps));
        return (float) ($this->base_salary + ($this->annual_raise * $step));
    }
}

