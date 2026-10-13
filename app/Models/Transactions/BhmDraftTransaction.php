<?php

namespace App\Models\Transactions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BhmDraftTransaction extends Model
{
    protected $table = 'bhm_draft_transactions';

    protected $fillable = [
        'ticket_number',
        'service_requested',
        'full_name',
        'national_id',
        'phone',
        'email',
        'parcel_number',
        'plot_number',
        'planning_file_number',
        'water_subscription_number',
        'property_address',
        'details',
        'status',
        'transaction_id',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(BhmTransaction::class, 'transaction_id');
    }
}
