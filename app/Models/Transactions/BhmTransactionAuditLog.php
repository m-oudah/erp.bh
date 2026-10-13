<?php

namespace App\Models\Transactions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BhmTransactionAuditLog extends Model
{
    protected $table = 'bhm_transaction_audit_logs';

    protected $fillable = [
        'transaction_id',
        'user_name',
        'action',
        'old_values',
        'new_values',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(BhmTransaction::class, 'transaction_id');
    }
}
