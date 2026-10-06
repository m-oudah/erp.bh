<?php
namespace App\Models\Buildings;
use Illuminate\Database\Eloquent\Model;

class BhmBuildingOwner extends Model {
    protected $table = 'bhm_building_owners';
    protected $guarded = [];
    public $timestamps = true;

    public function building() { return $this->belongsTo(BhmBuilding::class, 'building_id'); }
    public function units() { return $this->belongsToMany(BhmUnit::class, 'bhm_building_owner_unit', 'building_owner_id', 'unit_id'); }
    public function subscriptions() { return $this->hasMany(BhmSubscription::class, 'building_owner_id'); }

    public function getFullNameAttribute(): string {
        return trim(collect([$this->first_name, $this->second_name, $this->third_name, $this->sur_name])->filter()->implode(' '));
    }
}
