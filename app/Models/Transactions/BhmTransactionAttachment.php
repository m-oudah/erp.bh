<?php

namespace App\Models\Transactions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BhmTransactionAttachment extends Model
{
    protected $table = 'bhm_transaction_attachments';

    protected $fillable = [
        'transaction_id',
        'file_path',
        'file_label',
        'uploaded_by',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(BhmTransaction::class, 'transaction_id');
    }
}
