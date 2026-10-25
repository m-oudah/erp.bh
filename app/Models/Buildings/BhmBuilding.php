<?php

namespace App\Models\Buildings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BhmBuilding extends Model
{
    use SoftDeletes;

    protected $table = 'bhm_buildings';
    protected $guarded = [];

    public function zone()
    {
        return $this->belongsTo(BhmZone::class, 'zone_id');
    }

    public function subzone()
    {
        return $this->belongsTo(BhmSubzone::class, 'subzone_id');
    }

    public function street()
    {
        return $this->belongsTo(BhmStreet::class, 'street_id');
    }

    public function buildingType()
    {
        return $this->belongsTo(BhmBuildingType::class, 'building_type_id');
    }

    public function buildingStatus()
    {
        return $this->belongsTo(BhmBuildingStatus::class, 'building_status_id');
    }

    public function buildingPropertyType()
    {
        return $this->belongsTo(BhmBuildingPropertyType::class, 'building_property_type_id');
    }

    public function owners()
    {
        return $this->hasMany(BhmBuildingOwner::class, 'building_id');
    }

    public function getMainOwnerAttribute()
    {
        return $this->owners->first();
    }

    public function getDisplayNameAttribute(): string
    {
        $mainOwner = $this->main_owner;

        // إذا كانت بيانات المالك موجودة: أظهر اسم المالك الرئيسي
        if ($mainOwner && filled($mainOwner->full_name)) {
            return $mainOwner->full_name;
        }

        // إذا كانت بيانات المالك غير موجودة: أظهر رقم المبنى مثل ما كان سابقا و بين قوسين الاسم الاعتباري للمبنى building_name
        $numberText = filled($this->building_number) ? ('مبنى رقم ' . $this->building_number) : 'مبنى غير محدد';

        if (filled($this->building_name)) {
            return $numberText . ' (' . $this->building_name . ')';
        }

        return $numberText;
    }

    public function previousOwners()
    {
        return $this->hasMany(BhmPreviousOwner::class, 'building_id');
    }

    public function floors()
    {
        return $this->hasMany(BhmFloorDescription::class, 'building_id');
    }

    public function units()
    {
        return $this->hasMany(BhmUnit::class, 'building_id');
    }

    public function financial()
    {
        return $this->hasOne(BhmBuildFinancial::class, 'building_id');
    }

    public function licenseForm()
    {
        return $this->hasOne(BhmLicenseForm::class, 'building_id');
    }

    public function regulatoryReport()
    {
        return $this->hasOne(BhmRegulatoryDisclosureReport::class, 'building_id');
    }

    public function economicals()
    {
        return $this->hasMany(BhmEconomical::class, 'building_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(BhmSubscription::class, 'building_id');
    }

    public function proofs()
    {
        return $this->hasMany(BhmProofOfCase::class, 'building_id');
    }

    public function attachments()
    {
        return $this->hasMany(BhmAttachment::class, 'building_id');
    }

    public function uses()
    {
        return $this->belongsToMany(BhmBuildingUse::class, 'bhm_building_building_use', 'building_id', 'building_use_id');
    }

    public function materials()
    {
        return $this->belongsToMany(BhmBuildingMaterial::class, 'bhm_building_building_material', 'building_id', 'building_material_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(BhmSupervisor::class, 'supervisor_id');
    }

    // Accessors
    public function getOverallStatusLabelAttribute(): string
    {
        return match ($this->overall_status) {
            1 => 'ممتازة', 2 => 'جيدة', 3 => 'سيئة', default => 'غير محدد'
        };
    }

    public function getSewageLabelAttribute(): string
    {
        return match ($this->sewage) {
            1 => 'بلدية', 2 => 'بئر خاص', 3 => 'لا يوجد', default => 'غير محدد'
        };
    }

    public function getExternalStatusLabelAttribute(): string
    {
        return match ($this->out_status) {
            1 => 'مشطب كامل', 2 => 'مشطب جزئي', 3 => 'غير مشطب', default => 'غير محدد'
        };
    }

    // Scopes
    public function transactions()
    {
        return $this->hasMany(\App\Models\Transactions\BhmTransaction::class, 'building_id');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['file_number'] ?? null, fn($q, $v) => $q->where('file_number', $v));
        $query->when($filters['building_number'] ?? null, fn($q, $v) => $q->where('building_number', $v));
        $query->when($filters['block_number'] ?? null, fn($q, $v) => $q->where('block_number', $v));
        $query->when($filters['parcel_number'] ?? null, fn($q, $v) => $q->where('parcel_number', $v));
        $query->when($filters['building_name'] ?? null, function($q, $v) {
            $cleaned = preg_replace('/[+\-><\(\)~*\"@]+/', ' ', trim($v));
            $terms = array_filter(explode(' ', $cleaned));
            if (!empty($terms)) {
                $boolQuery = implode(' ', array_map(fn($t) => '+' . $t . '*', $terms));
                $q->where(function($sub) use ($boolQuery, $v) {
                    $sub->whereRaw("MATCH(building_name) AGAINST(? IN BOOLEAN MODE)", [$boolQuery])
                        ->orWhere('building_name', 'like', "%{$v}%");
                });
            } else {
                $q->where('building_name', 'like', "%{$v}%");
            }
        });
        $query->when($filters['zone_id'] ?? null, fn($q, $v) => $q->where('zone_id', $v));
        $query->when($filters['street_number'] ?? null, function($q, $v) {
            $q->whereHas('street', fn($sq) => $sq->where('street_number', $v));
        });
        $query->when($filters['street_id'] ?? null, function($q, $v) {
            $q->where(function($sub) use ($v) {
                $sub->where('street_id', $v)
                    ->orWhereHas('street', fn($sq) => $sq->where('street_number', $v));
            });
        });
        $query->when($filters['building_type_id'] ?? null, fn($q, $v) => $q->where('building_type_id', $v));
        $query->when($filters['id_card'] ?? null, fn($q, $v) => $q->whereHas('owners', fn($q) => $q->where('id_card', $v)));
        $query->when($filters['owner_name'] ?? null, fn($q, $v) => $q->whereHas('owners', fn($q) => $q->where('first_name', 'like', "%{$v}%")->orWhere('sur_name', 'like', "%{$v}%")));
        
        // Construction license status & flags
        if (isset($filters['construction_status']) && $filters['construction_status'] !== '') {
            $query->where('construction_status', $filters['construction_status']);
        }
        if (!empty($filters['historical'])) {
            $query->where('historical', 1);
        }
        if (!empty($filters['abandoned'])) {
            $query->where('abandond', 1);
        }
        if (!empty($filters['adding_new_floor'])) {
            $query->where('adding_new_floor', 1);
        }
    }
}
