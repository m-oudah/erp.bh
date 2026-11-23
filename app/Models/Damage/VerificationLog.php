<?php

namespace App\Models\Damage;

use Illuminate\Database\Eloquent\Model;

class VerificationLog extends Model
{
    public $timestamps = false;
    protected $table = 'dams_verification_logs';

    protected $fillable = [
        'donor_id',
        'searched_identifier',
        'query_type',
        'result_found',
        'records_matched_count',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'result_found' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function donor()
    {
        return $this->belongsTo(DonorOrganization::class, 'donor_id');
    }
}
