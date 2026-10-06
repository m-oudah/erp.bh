<?php
namespace App\Models\Buildings;
use Illuminate\Database\Eloquent\Model;

class BhmRegulatoryDisclosureReport extends Model {
    protected $table = 'bhm_regulatory_disclosure_reports';
    protected $guarded = [];
    public function building() { return $this->belongsTo(BhmBuilding::class, 'building_id'); }
    public function licenseForm() { return $this->belongsTo(BhmLicenseForm::class, 'license_form_id'); }
    public function getPropertyLabelAttribute(): string { return $this->isproperty ? 'يملك' : 'مستأجر'; }
    public function getSortedLabelAttribute(): string { return $this->isorted ? 'مفروزة' : 'غير مفروزة'; }
    public function getLocationLabelAttribute(): string {
        return match ((int)$this->location_status) { 1 => 'فراغ', 2 => 'تحت الإنشاء', 3 => 'تام الإنشاء', default => 'غير محدد' };
    }
}
