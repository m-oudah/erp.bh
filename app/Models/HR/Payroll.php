<?php

namespace App\Models\HR;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hr_payrolls';

    protected $fillable = [
        'payroll_number',
        'year',
        'month',
        'title',
        'status',
        'total_employees',
        'total_basic_salary',
        'total_allowances',
        'total_deductions',
        'total_net_salary',
        'notes',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'year' => 'integer',
        'month' => 'integer',
        'total_employees' => 'integer',
        'total_basic_salary' => 'decimal:2',
        'total_allowances' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'total_net_salary' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public const STATUSES = [
        'draft' => 'مسودة قيد المراجعة والتعديل',
        'approved' => 'معتمد رسمياً',
        'paid' => 'مصروع ومغلق',
    ];

    public function items()
    {
        return $this->hasMany(PayrollItem::class, 'payroll_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getStatusArabicAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getMonthNameArabicAttribute(): string
    {
        $months = [
            1 => 'يناير (1)',
            2 => 'فبراير (2)',
            3 => 'مارس (3)',
            4 => 'أبريل (4)',
            5 => 'مايو (5)',
            6 => 'يونيو (6)',
            7 => 'يوليو (7)',
            8 => 'أغسطس (8)',
            9 => 'سبتمبر (9)',
            10 => 'أكتوبر (10)',
            11 => 'نوفمبر (11)',
            12 => 'ديسمبر (12)',
        ];

        return $months[$this->month] ?? (string) $this->month;
    }

    /**
     * Recalculate summary totals from its payroll items
     */
    public function recalculateTotals(): void
    {
        $items = $this->items;
        $this->update([
            'total_employees' => $items->count(),
            'total_basic_salary' => $items->sum('base_salary'),
            'total_allowances' => $items->sum('total_allowances'),
            'total_deductions' => $items->sum('total_deductions'),
            'total_net_salary' => $items->sum('net_salary'),
        ]);
    }
}

