<?php
namespace App\Models\Buildings;
use Illuminate\Database\Eloquent\Model;

class BhmZone extends Model {
    protected $table = 'bhm_zones';
    protected $guarded = [];
    public function subzones() { return $this->hasMany(BhmSubzone::class, 'zone_id'); }
    public function streets() { return $this->hasMany(BhmStreet::class, 'zone_id'); }
    public function buildings() { return $this->hasMany(BhmBuilding::class, 'zone_id'); }
}
