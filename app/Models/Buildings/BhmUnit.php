<?php

namespace App\Models\Buildings;

use Illuminate\Database\Eloquent\Model;

class BhmUnit extends Model
{
    protected $table = 'bhm_units';
    protected $guarded = [];

    public function building()
    {
        return $this->belongsTo(BhmBuilding::class, 'building_id');
    }

    public function floor()
    {
        return $this->belongsTo(BhmFloorDescription::class, 'floor_id');
    }

    public function buildingOwner()
    {
        return $this->belongsTo(BhmBuildingOwner::class, 'building_owner_id');
    }

    public function owners()
    {
        return $this->belongsToMany(BhmBuildingOwner::class, 'bhm_building_owner_unit', 'unit_id', 'building_owner_id');
    }

    public function unitOwners()
    {
        return $this->hasMany(BhmUnitOwner::class, 'unit_id');
    }

    public function uses()
    {
        return $this->hasOne(BhmUnitUser::class, 'unit_id');
    }

    public function unitUser()
    {
        return $this->hasOne(BhmUnitUser::class, 'unit_id');
    }

    public function getUnitTypeLabelAttribute(): string
    {
        return match ((int)$this->unit_type) {
            1 => 'سكني',
            2 => 'تجاري',
            3 => 'صناعي',
            11 => 'خدمات / أخرى',
            default => 'غير محدد'
        };
    }

    public function getUsagesListAttribute(): array
    {
        $usages = [];
        if ($this->residential) $usages[] = 'سكني';
        if ($this->commercial) $usages[] = 'تجاري';
        if ($this->industrial) $usages[] = 'صناعي';
        if ($this->educational) $usages[] = 'تعليمي';
        if ($this->cultural) $usages[] = 'ثقافي';
        if ($this->health) $usages[] = 'صحي';
        if ($this->tourism) $usages[] = 'سياحي';
        if ($this->religous) $usages[] = 'ديني / مسجد';
        if ($this->institutions) $usages[] = 'مؤسسات';
        if ($this->other_usage) $usages[] = 'أخرى';

        if (empty($usages)) {
            $label = $this->unit_type_label;
            if ($label !== 'غير محدد') {
                $usages[] = $label;
            }
        }

        return $usages;
    }

    public function getAllOwnersAttribute()
    {
        if ($this->relationLoaded('unitOwners') && $this->unitOwners->isNotEmpty()) {
            return $this->unitOwners;
        }
        if ($this->relationLoaded('owners') && $this->owners->isNotEmpty()) {
            return $this->owners;
        }
        if ($this->buildingOwner) {
            return collect([$this->buildingOwner]);
        }
        if ($this->relationLoaded('building') && $this->building && $this->building->owners->isNotEmpty()) {
            return $this->building->owners;
        }
        // If not eager-loaded, query directly
        $direct = $this->unitOwners()->get();
        if ($direct->isNotEmpty()) {
            return $direct;
        }
        $pivot = $this->owners()->get();
        if ($pivot->isNotEmpty()) {
            return $pivot;
        }
        if ($this->building && $this->building->owners()->count() > 0) {
            return $this->building->owners()->get();
        }

        return collect([]);
    }

    public function getDisplayNameAttribute(): string
    {
        if ($this->unit_name) {
            return $this->unit_name;
        }
        $type = $this->unit_type_label !== 'غير محدد' ? $this->unit_type_label : 'وحدة';
        return "{$type} رقم " . ($this->unit_number ?: $this->id);
    }

    public function scopeFilter($query, array $filters)
    {
        // General text search
        if (!empty($filters['search'])) {
            $s = trim($filters['search']);
            $query->where(function ($q) use ($s) {
                $q->where('unit_number', 'like', "%{$s}%")
                  ->orWhere('unit_name', 'like', "%{$s}%")
                  ->orWhere('profession_name', 'like', "%{$s}%")
                  ->orWhere('building_number', 'like', "%{$s}%")
                  ->orWhere('street_number', 'like', "%{$s}%")
                  ->orWhere('form_number', 'like', "%{$s}%")
                  ->orWhere('system_number', 'like', "%{$s}%")
                  ->orWhereHas('building', function ($bq) use ($s) {
                      $bq->where('building_number', 'like', "%{$s}%")
                         ->orWhere('file_number', 'like', "%{$s}%")
                         ->orWhere('building_name', 'like', "%{$s}%")
                         ->orWhere('block_number', 'like', "%{$s}%")
                         ->orWhere('parcel_number', 'like', "%{$s}%");
                  })
                  ->orWhereHas('unitOwners', function ($oq) use ($s) {
                      $oq->where('id_card', 'like', "%{$s}%")
                         ->orWhere('first_name', 'like', "%{$s}%")
                         ->orWhere('sur_name', 'like', "%{$s}%")
                         ->orWhere('phone_number', 'like', "%{$s}%");
                  })
                  ->orWhereHas('building.owners', function ($boq) use ($s) {
                      $boq->where('id_card', 'like', "%{$s}%")
                          ->orWhere('first_name', 'like', "%{$s}%")
                          ->orWhere('sur_name', 'like', "%{$s}%")
                          ->orWhere('phone_number', 'like', "%{$s}%");
                  });
            });
        }

        // Specific filters
        if (!empty($filters['unit_number'])) {
            $query->where('unit_number', $filters['unit_number']);
        }

        if (!empty($filters['building_id'])) {
            $query->where('building_id', $filters['building_id']);
        }

        if (!empty($filters['building_number'])) {
            $bNum = $filters['building_number'];
            $query->where(function ($q) use ($bNum) {
                $q->where('building_number', $bNum)
                  ->orWhereHas('building', fn($bq) => $bq->where('building_number', $bNum));
            });
        }

        if (!empty($filters['street_number'])) {
            $sNum = $filters['street_number'];
            $query->where(function ($q) use ($sNum) {
                $q->where('street_number', $sNum)
                  ->orWhereHas('building', fn($bq) => $bq->where('street_id', $sNum));
            });
        }

        if (!empty($filters['floor_number']) || (isset($filters['floor_number']) && $filters['floor_number'] === '0')) {
            $query->where('floor_number', $filters['floor_number']);
        }

        if (!empty($filters['unit_type'])) {
            $query->where('unit_type', $filters['unit_type']);
        }

        if (!empty($filters['usage'])) {
            $usage = $filters['usage'];
            match ($usage) {
                'residential' => $query->where('residential', 1),
                'commercial' => $query->where('commercial', 1),
                'industrial' => $query->where('industrial', 1),
                'educational' => $query->where('educational', 1),
                'health' => $query->where('health', 1),
                'religous' => $query->where('religous', 1),
                'cultural' => $query->where('cultural', 1),
                'other' => $query->where('other_usage', 1),
                default => null
            };
        }

        if (!empty($filters['id_card'])) {
            $idCard = trim($filters['id_card']);
            $query->where(function ($q) use ($idCard) {
                $q->whereHas('unitOwners', fn($oq) => $oq->where('id_card', 'like', "%{$idCard}%"))
                  ->orWhereHas('building.owners', fn($boq) => $boq->where('id_card', 'like', "%{$idCard}%"));
            });
        }

        if (!empty($filters['owner_name'])) {
            $oName = trim($filters['owner_name']);
            $query->where(function ($q) use ($oName) {
                $q->whereHas('unitOwners', function ($oq) use ($oName) {
                    $oq->where('first_name', 'like', "%{$oName}%")
                       ->orWhere('second_name', 'like', "%{$oName}%")
                       ->orWhere('third_name', 'like', "%{$oName}%")
                       ->orWhere('sur_name', 'like', "%{$oName}%");
                })->orWhereHas('building.owners', function ($boq) use ($oName) {
                    $boq->where('first_name', 'like', "%{$oName}%")
                        ->orWhere('second_name', 'like', "%{$oName}%")
                        ->orWhere('third_name', 'like', "%{$oName}%")
                        ->orWhere('sur_name', 'like', "%{$oName}%");
                });
            });
        }

        return $query;
    }
}
