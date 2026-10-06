<?php
namespace App\Models\Buildings;
use Illuminate\Database\Eloquent\Model;

class BhmSubzone extends Model {
    protected $table = 'bhm_subzones';
    protected $guarded = [];
    public function zone() { return $this->belongsTo(BhmZone::class, 'zone_id'); }
    public function streets() { return $this->hasMany(BhmStreet::class, 'subzone_id'); }
    public function buildings() { return $this->hasMany(BhmBuilding::class, 'subzone_id'); }
}
