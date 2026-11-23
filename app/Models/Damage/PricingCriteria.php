<?php

namespace App\Models\Damage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PricingCriteria extends Model
{
    use HasFactory;

    protected $table = 'dams_pricing_criteria';

    protected $fillable = [
        'name_ar',
        'name_en',
        'sector',
        'category',
        'unit_of_measure',
        'unit_type',
        'unit_price_usd',
        'unit_price_ils',
        'damage_multipliers',
        'formula_type',
        'notes',
        'description',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'unit_price_usd' => 'decimal:2',
        'unit_price_ils' => 'decimal:2',
        'damage_multipliers' => 'array',
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function damageRecords()
    {
        return $this->hasMany(DamageRecord::class, 'pricing_criteria_id');
    }

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

    public function getUnitTypeAttribute(): string
    {
        return $this->unit_of_measure ?? 'sqm';
    }

    public function setUnitTypeAttribute($value)
    {
        $this->attributes['unit_of_measure'] = $value;
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->notes;
    }

    public function setDescriptionAttribute($value)
    {
        $this->attributes['notes'] = $value;
    }

    public function getUnitLabelAttribute(): string
    {
        return match ($this->unit_of_measure) {
            'sqm' => 'متر مربع (م²)',
            'linear_meter' => 'متر طولي (م.ط)',
            'item' => 'عدد / وحدة',
            'lump_sum' => 'مقطوعية',
            default => $this->unit_of_measure,
        };
    }
}
