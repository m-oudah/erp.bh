<?php
namespace App\Models\Buildings;
use Illuminate\Database\Eloquent\Model;

class BhmFloorDescription extends Model {
    protected $table = 'bhm_floors_descriptions';
    protected $guarded = [];

    public function building() { return $this->belongsTo(BhmBuilding::class, 'building_id'); }
    public function units() { return $this->hasMany(BhmUnit::class, 'floor_id'); }
    public function developments() { return $this->hasMany(BhmDevelopmentData::class, 'floor_description_id'); }

    public function getFloorNameAttribute(): string {
        return match ((int)$this->floor_number) {
            0 => 'أرضي سكني', 100 => 'أرضي تجاري',
            1 => 'الأول', 2 => 'الثاني', 3 => 'الثالث',
            4 => 'الرابع', 5 => 'الخامس', 6 => 'السادس',
            7 => 'السابع', 8 => 'الثامن', 10 => 'بدروم',
            11 => 'بركس تجاري', 12 => 'بركس مزارع دواجن',
            13 => 'بركس مزارع أبقار', 14 => 'بركس',
            default => 'غير محدد'
        };
    }

    public function getLicenseStatusLabelAttribute(): string {
        return match ((int)$this->is_licensed) {
            1 => 'غير مرخص', 2 => 'مرخص وغير مستوفي الرسوم',
            3 => 'مرخص ومتبقي طوابق غير مرخصة', 4 => 'مرخص ومستوفي الرسوم',
            default => 'غير محدد'
        };
    }
}
