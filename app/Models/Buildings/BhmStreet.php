<?php
namespace App\Models\Buildings;
use Illuminate\Database\Eloquent\Model;

class BhmStreet extends Model {
    protected $table = 'bhm_streets';
    protected $guarded = [];
    public function zone() { return $this->belongsTo(BhmZone::class, 'zone_id'); }
    public function subzone() { return $this->belongsTo(BhmSubzone::class, 'subzone_id'); }
    public function buildings() { return $this->hasMany(BhmBuilding::class, 'street_id'); }
}
