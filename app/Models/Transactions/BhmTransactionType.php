<?php

namespace App\Models\Transactions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BhmTransactionType extends Model
{
    protected $table = 'bhm_transaction_types';

    protected $fillable = [
        'name',
        'sla_days',
        'default_depts',
        'responsible_dept',
        'request_fees',
        'required_docs',
        'execution_steps',
    ];

    protected $casts = [
        'default_depts' => 'array',
        'required_docs' => 'array',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(BhmTransaction::class, 'transaction_type_id');
    }
}
