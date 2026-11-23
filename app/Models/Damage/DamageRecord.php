<?php

namespace App\Models\Damage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Buildings\BhmBuilding;
use App\Models\Buildings\BhmUnit;
use App\Models\Buildings\BhmStreet;
use App\Models\Buildings\BhmZone;
use App\Models\Buildings\BhmSubzone;
use App\Models\User;
use Illuminate\Support\Str;

class DamageRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dams_damage_records';

    protected $fillable = [
        'record_number',
        'sector',
        'entity_type',
        'entity_id',
        'zone_id',
        'subzone_id',
        'street_id',
        'building_id',
        'building_number',
        'unit_number',
        'file_number',
        'block_number',
        'parcel_number',
        'name',
        'latitude',
        'longitude',
        'geojson_geometry',
        'damage_level',
        'operational_status',
        'area_sqm',
        'floors_count',
        'units_count',
        'length_meters',
        'width_meters',
        'road_type',
        'associated_infrastructure',
        'structure_type',
        'facility_type',
        'capacity',
        'operating_entity',
        'pricing_criteria_id',
        'estimated_cost_usd',
        'estimated_cost_ils',
        'pricing_breakdown',
        'workflow_status',
        'audit_created_by',
        'audit_verified_by',
        'audit_approved_by',
        'initial_assessment_date',
        'engineering_inspection_date',
        'approval_date',
        'rejection_reason',
        'engineering_notes',
        'notes',
        'citizen_national_id',
        'citizen_name',
        'citizen_phone',
        'certificate_uuid',
        'certificate_qr_hash',
        'certificate_issued_at',
        'certificate_downloads_count',
        'is_baseline_auto',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'geojson_geometry' => 'array',
        'associated_infrastructure' => 'array',
        'pricing_breakdown' => 'array',
        'area_sqm' => 'decimal:2',
        'length_meters' => 'decimal:2',
        'width_meters' => 'decimal:2',
        'estimated_cost_usd' => 'decimal:2',
        'estimated_cost_ils' => 'decimal:2',
        'is_baseline_auto' => 'boolean',
        'initial_assessment_date' => 'date',
        'engineering_inspection_date' => 'date',
        'approval_date' => 'date',
        'certificate_issued_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($record) {
            if (empty($record->record_number)) {
                $year = date('Y');
                $last = static::whereYear('created_at', $year)->max('id') ?? 0;
                $record->record_number = 'DAM-' . $year . '-' . sprintf('%05d', $last + 1);
            }
            if (empty($record->certificate_uuid)) {
                $record->certificate_uuid = (string) Str::uuid();
                $record->certificate_qr_hash = hash('sha256', $record->certificate_uuid . '-' . config('app.key'));
            }
        });

        static::saving(function ($record) {
            if (is_null($record->length_meters)) {
                $record->length_meters = 0.0;
            }
            if (is_null($record->width_meters)) {
                $record->width_meters = 0.0;
            }
            if (is_null($record->area_sqm)) {
                $record->area_sqm = 0.0;
            }
            if (is_null($record->floors_count)) {
                $record->floors_count = 1;
            }
            if (is_null($record->units_count)) {
                $record->units_count = 1;
            }
            if (is_null($record->estimated_cost_usd)) {
                $record->estimated_cost_usd = 0.0;
            }
            if (is_null($record->estimated_cost_ils)) {
                $record->estimated_cost_ils = 0.0;
            }
            if (empty($record->entity_type)) {
                $sectorMap = [
                    'real_estate' => 'building',
                    'infrastructure' => 'street',
                    'public_facilities' => 'facility',
                    'economic_crafts' => 'craft',
                    'municipal_assets' => 'asset',
                ];
                $record->entity_type = $sectorMap[$record->sector] ?? 'building';
            }
        });
    }

    // Relationships
    public function building()
    {
        return $this->belongsTo(BhmBuilding::class, 'building_id');
    }

    public function unit()
    {
        return $this->belongsTo(BhmUnit::class, 'entity_id')->where('entity_type', 'unit');
    }

    public function street()
    {
        return $this->belongsTo(BhmStreet::class, 'street_id');
    }

    public function zone()
    {
        return $this->belongsTo(BhmZone::class, 'zone_id');
    }

    public function subzone()
    {
        return $this->belongsTo(BhmSubzone::class, 'subzone_id');
    }

    public function pricingCriteria()
    {
        return $this->belongsTo(PricingCriteria::class, 'pricing_criteria_id');
    }

    public function auditLogs()
    {
        return $this->hasMany(DamageAuditLog::class, 'record_id')->latest();
    }

    public function attachments()
    {
        return $this->hasMany(DamageAttachment::class, 'record_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'audit_created_by');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'audit_verified_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'audit_approved_by');
    }

    // Accessors & Labels
    public function getSectorLabelAttribute(): string
    {
        return match ($this->sector) {
            'real_estate' => 'المباني والوحدات العقارية',
            'infrastructure' => 'البنية التحتية والشبكات',
            'municipal_assets' => 'ممتلكات وأصول البلدية',
            'public_facilities' => 'المرافق العامة والحيوية',
            'economic_crafts' => 'القطاع الاقتصادي والحرفي',
            default => $this->sector,
        };
    }

    public function getDamageLevelLabelAttribute(): string
    {
        return match ($this->damage_level) {
            'total_destruction' => 'مهدم كلياً (تدمير شامل)',
            'severe_damage', 'severe_partial' => 'ضرر جزئي بليغ (غير صالح للسكن)',
            'partial_damage', 'moderate_partial' => 'ضرر جزئي متوسط (قابل للتأهيل)',
            'minor_damage', 'minor' => 'ضرر جزئي طفيف',
            'intact' => 'سليم / بدون ضرر ظاهر',
            default => $this->damage_level ?? 'غير محدد',
        };
    }

    public function getDamageLevelArabicAttribute(): string
    {
        return $this->damage_level_label;
    }

    public function getDamageBadgeColorAttribute(): string
    {
        return match ($this->damage_level) {
            'total_destruction' => 'bg-rose-100 text-rose-800 border-rose-300',
            'severe_damage', 'severe_partial' => 'bg-orange-100 text-orange-800 border-orange-300',
            'partial_damage', 'moderate_partial' => 'bg-amber-100 text-amber-800 border-amber-300',
            'minor_damage', 'minor' => 'bg-blue-100 text-blue-800 border-blue-300',
            'intact' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            default => 'bg-slate-100 text-slate-800 border-slate-300',
        };
    }

    public function getWorkflowStatusLabelAttribute(): string
    {
        return match ($this->workflow_status) {
            'draft' => 'إدخال أولي / مبدئي',
            'engineering_review' => 'مراجعة وتدقيق هندسي',
            'final_approved' => 'معتمد نهائياً',
            'rejected' => 'مرفوض',
            default => $this->workflow_status,
        };
    }

    public function getWorkflowBadgeColorAttribute(): string
    {
        return match ($this->workflow_status) {
            'draft' => 'bg-slate-100 text-slate-700 border-slate-300',
            'engineering_review' => 'bg-blue-50 text-blue-700 border-blue-200',
            'final_approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
            default => 'bg-slate-100 text-slate-700 border-slate-300',
        };
    }

    public function getRoadTypeLabelAttribute(): string
    {
        return match ($this->road_type) {
            'asphalt' => 'إسفلت',
            'interlock' => 'إنترلوك / بلاط',
            'dirt' => 'ترابي / غير معبد',
            default => $this->road_type ?? '—',
        };
    }

    public function getCertificateUrlAttribute(): string
    {
        return url('/verify-certificate/' . $this->certificate_uuid);
    }
}
