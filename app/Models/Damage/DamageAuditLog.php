<?php

namespace App\Models\Damage;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class DamageAuditLog extends Model
{
    public $timestamps = false;
    protected $table = 'dams_audit_logs';

    protected $fillable = [
        'record_id',
        'user_id',
        'action',
        'old_values',
        'new_values',
        'notes',
        'ip_address',
        'created_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    public function record()
    {
        return $this->belongsTo(DamageRecord::class, 'record_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'created' => 'إنشاء السجل',
            'bulk_imported' => 'تأسيس أولي جماعي',
            'damage_level_changed' => 'تعديل مستوى الضرر',
            'pricing_recalculated' => 'إعادة احتساب مالي',
            'workflow_transition' => 'تغيير حالة الاعتماد',
            'notes_added' => 'إضافة ملاحظات',
            default => $this->action,
        };
    }
}
