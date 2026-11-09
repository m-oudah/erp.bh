<?php

namespace App\Models\Transactions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Buildings\BhmCustomer;
use App\Models\Buildings\BhmBuilding;

class BhmTransaction extends Model
{
    use SoftDeletes;

    protected $table = 'bhm_transactions';

    protected $fillable = [
        'transaction_number',
        'transaction_type_id',
        'customer_id',
        'building_id',
        'details',
        'status',
        'mayor_opinion',
        'rejection_reason',
    ];

    public function transactionType(): BelongsTo
    {
        return $this->belongsTo(BhmTransactionType::class, 'transaction_type_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(BhmCustomer::class, 'customer_id');
    }

    public function building(): BelongsTo
    {
        return $this->belongsTo(BhmBuilding::class, 'building_id');
    }

    public function departmentResponses(): HasMany
    {
        return $this->hasMany(BhmDepartmentResponse::class, 'transaction_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(BhmTransactionAttachment::class, 'transaction_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(BhmTransactionAuditLog::class, 'transaction_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'قيد الدراسة والمتابعة',
            'approved' => 'معتمدة ومكتملة',
            'returned' => 'مرجعة للاستيفاء',
            'closed' => 'مغلقة',
            default => $this->status ?? 'قيد الدراسة',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-amber-50 text-amber-800 border border-amber-200',
            'approved' => 'bg-emerald-50 text-emerald-800 border border-emerald-200',
            'returned' => 'bg-rose-50 text-rose-800 border border-rose-200',
            'closed' => 'bg-slate-100 text-slate-700 border border-slate-200',
            default => 'bg-gray-100 text-gray-700 border border-gray-200',
        };
    }

    public function getIsDelayedAttribute(): bool
    {
        if ($this->status !== 'pending') {
            return false;
        }
        if (!$this->transactionType) {
            return false;
        }
        $created = \Carbon\Carbon::parse($this->created_at);
        return $created->diffInDays(\Carbon\Carbon::now()) >= $this->transactionType->sla_days;
    }

    public static function generateTransactionNumber(): string
    {
        $year = date('Y');
        $prefix = "TX-{$year}-";

        $maxSuffix = static::withTrashed()
            ->where('transaction_number', 'like', "$prefix%")
            ->selectRaw('MAX(CAST(SUBSTRING_INDEX(transaction_number, "-", -1) AS UNSIGNED)) as max_suffix')
            ->value('max_suffix');

        $nextNumber = $maxSuffix ? ((int) $maxSuffix + 1) : 1001;
        if ($nextNumber < 1001) {
            $nextNumber = 1001;
        }

        return $prefix . $nextNumber;
    }
}
