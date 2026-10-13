<?php

namespace App\Models\Transactions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BhmDepartmentResponse extends Model
{
    protected $table = 'bhm_department_responses';

    protected $fillable = [
        'transaction_id',
        'department_name',
        'response_text',
        'attachment_path',
        'status',
        'is_latest',
        'duration_days',
        'is_delayed',
        'responded_at',
    ];

    protected $casts = [
        'is_latest' => 'boolean',
        'is_delayed' => 'boolean',
        'responded_at' => 'datetime',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(BhmTransaction::class, 'transaction_id');
    }
}
