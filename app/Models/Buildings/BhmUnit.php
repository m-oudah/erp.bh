<?php
namespace App\Models\Buildings;
use Illuminate\Database\Eloquent\Model;

class BhmUnit extends Model {
    protected $table = 'bhm_units';
    protected $guarded = [];

    public function building() { return $this->belongsTo(BhmBuilding::class, 'building_id'); }
    public function floor() { return $this->belongsTo(BhmFloorDescription::class, 'floor_id'); }
    public function buildingOwner() { return $this->belongsTo(BhmBuildingOwner::class, 'building_owner_id'); }
    public function owners() { return $this->belongsToMany(BhmBuildingOwner::class, 'bhm_building_owner_unit', 'unit_id', 'building_owner_id'); }
    public function uses() { return $this->hasOne(BhmUnitUser::class, 'unit_id'); }

    public function getUnitTypeLabelAttribute(): string {
        return match ((int)$this->unit_type) { 1 => 'سكني', 2 => 'تجاري', default => 'غير محدد' };
    }
}
