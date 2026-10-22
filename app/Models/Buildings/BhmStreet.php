<?php
namespace App\Models\Buildings;
use Illuminate\Database\Eloquent\Model;

class BhmStreet extends Model {
    protected $table = 'bhm_streets';
    protected $guarded = [];
    public function zone() { return $this->belongsTo(BhmZone::class, 'zone_id'); }
    public function subzone() { return $this->belongsTo(BhmSubzone::class, 'subzone_id'); }
    public function buildings() { return $this->hasMany(BhmBuilding::class, 'street_id'); }

    public function getDisplayNameAttribute(): string
    {
        $name = trim($this->street_name ?? '');
        $number = trim($this->street_number ?? '');

        if (filled($name) && $name !== $number) {
            return $name . (filled($number) ? ' (' . $number . ')' : '');
        }

        return filled($number) ? ('شارع ' . $number) : (filled($name) ? $name : '—');
    }
}
