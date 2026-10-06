<?php
namespace App\Models\Buildings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BhmSubscription extends Model {
    protected $table = 'bhm_subscriptions';
    protected $guarded = [];
    public function building() { return $this->belongsTo(BhmBuilding::class, 'building_id'); }
    public function buildingOwner() { return $this->belongsTo(BhmBuildingOwner::class, 'building_owner_id'); }
    public function units() { return $this->belongsToMany(BhmUnit::class, 'bhm_subscription_unit', 'subscription_id', 'unit_id'); }
}
