<?php
namespace App\Models\Buildings;
use Illuminate\Database\Eloquent\Model;

class BhmEconomical extends Model {
    protected $table = 'bhm_economical';
    protected $guarded = [];
    public function building() { return $this->belongsTo(BhmBuilding::class, 'building_id'); }
    public function unit() { return $this->belongsTo(BhmUnit::class, 'unit_id'); }
    public function sector() { return $this->belongsTo(BhmEconomicalSector::class, 'job_sector_id'); }
    public function owners() { return $this->hasMany(BhmEconomicalOwner::class, 'economical_id'); }
    public function attachments() { return $this->hasMany(BhmCraftAttachment::class, 'economical_id'); }
    public function crafts() { return $this->hasMany(BhmCraftTypeEconomical::class, 'economical_id'); }


    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['id_card'] ?? null, fn($q, $v) => $q->where('id_card', $v)->orWhereHas('owners', fn($q) => $q->where('id_card', $v)));
        $query->when($filters['name'] ?? null, fn($q, $v) => $q->whereHas('owners', fn($q) => $q->where('first_name', 'like', "%{$v}%")->orWhere('sur_name', 'like', "%{$v}%")->orWhere('second_name', 'like', "%{$v}%")));
        $query->when($filters['trade_name'] ?? null, fn($q, $v) => $q->where('job_formal_name', 'like', "%{$v}%"));
        $query->when($filters['job_sector_id'] ?? null, fn($q, $v) => $q->where('job_sector_id', $v));
        // isLicensed: 1 = مرخص, 2 = غير مرخص (قيم النظام القديم)
        $query->when(($filters['isLicensed'] ?? '') !== '', fn($q) => $q->where('isLicensed', $filters['isLicensed'] == '1' ? 1 : 2));
        // isDanger: 1 = خطرة, 0 = عادية
        $query->when(($filters['isDanger'] ?? '') !== '', fn($q) => $q->where('isDanger', $filters['isDanger']));
        $query->when($filters['craft_type_id'] ?? null, fn($q, $v) => $q->whereHas('crafts', fn($q) => $q->where('craft_type_id', $v)));
    }
}
