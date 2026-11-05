<?php

namespace App\Models\Portal;

use Illuminate\Database\Eloquent\Model;

class CitizenAccessLog extends Model
{
    protected $table = 'citizen_access_logs';

    protected $guarded = [];

    public static function logAction(string $idCard, ?string $mobile, ?string $name, string $action, ?string $details = null): self
    {
        return self::create([
            'id_card' => $idCard,
            'mobile' => $mobile,
            'citizen_name' => $name,
            'action' => $action,
            'ip_address' => request()->ip(),
            'details' => $details,
        ]);
    }
}

