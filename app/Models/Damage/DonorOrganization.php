<?php

namespace App\Models\Damage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class DonorOrganization extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'dams_donor_organizations';

    protected $fillable = [
        'name_ar',
        'name_en',
        'organization_type',
        'contact_person',
        'email',
        'phone',
        'password',
        'status',
        'api_key',
        'allowed_sectors',
        'notes',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'api_key',
    ];

    protected $casts = [
        'allowed_sectors' => 'array',
    ];

    public function verificationLogs()
    {
        return $this->hasMany(VerificationLog::class, 'donor_id');
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->organization_type) {
            'un_agency' => 'وكالة أو منظمة أممية (UN)',
            'international_ngo' => 'منظمة دولية غير حكومية (INGO)',
            'local_ngo' => 'مؤسسة أو جمعية أهلية محلية',
            'ministry' => 'وزارة أو هيئة حكومية',
            'donor' => 'صندوق أو جهة مانحة',
            default => 'مؤسسة معتمدة',
        };
    }
}
