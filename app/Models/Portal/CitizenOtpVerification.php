<?php

namespace App\Models\Portal;

use Illuminate\Database\Eloquent\Model;

class CitizenOtpVerification extends Model
{
    protected $table = 'citizen_otp_verifications';

    protected $guarded = [];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}

