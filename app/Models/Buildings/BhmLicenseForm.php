<?php
namespace App\Models\Buildings;
use Illuminate\Database\Eloquent\Model;

class BhmLicenseForm extends Model {
    protected $table = 'bhm_license_forms';
    protected $guarded = [];

    public function building() { return $this->belongsTo(BhmBuilding::class, 'building_id'); }
    public function floors() { return $this->hasMany(BhmFloorDescription::class, 'license_form_id'); }
    public function attachments() { return $this->hasMany(BhmAttachment::class, 'license_form_id'); }
    public function report() { return $this->hasOne(BhmRegulatoryDisclosureReport::class, 'license_form_id'); }
    public function owner() { return $this->hasOne(BhmBuildingOwner::class, 'license_form_id'); }

    public function legalOpinionReply() { return $this->belongsTo(BhmLicenseFormReply::class, 'legal_opinion'); }
    public function areaOpinionReply() { return $this->belongsTo(BhmLicenseFormReply::class, 'area_opinion'); }
    public function planOpinionReply() { return $this->belongsTo(BhmLicenseFormReply::class, 'plan_opinion'); }
    public function waterOpinionReply() { return $this->belongsTo(BhmLicenseFormReply::class, 'water_opinion'); }
    public function sewerOpinionReply() { return $this->belongsTo(BhmLicenseFormReply::class, 'sewer_opinion'); }
    public function collectionOpinionReply() { return $this->belongsTo(BhmLicenseFormReply::class, 'collection_opinion'); }
    public function gisOpinionReply() { return $this->belongsTo(BhmLicenseFormReply::class, 'gis_opinion'); }

    public function getFullNameAttribute(): string {
        return trim(collect([$this->first_name, $this->second_name, $this->third_name, $this->sur_name])->filter()->implode(' '));
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['id_card'] ?? null, fn($q, $v) => $q->where('id_card', $v));
        $query->when($filters['name'] ?? null, fn($q, $v) => $q->where('first_name', 'like', "%{$v}%")->orWhere('sur_name', 'like', "%{$v}%")->orWhere('second_name', 'like', "%{$v}%"));
        $query->when($filters['status'] ?? null, fn($q, $v) => $q->where('status', $v));
        $query->when($filters['building_id'] ?? null, fn($q, $v) => $q->where('building_id', $v));
        $query->when($filters['building_number'] ?? null, fn($q, $v) => $q->where('building_number', 'like', "%{$v}%"));
        $query->when($filters['phone'] ?? null, fn($q, $v) => $q->where('phone', 'like', "%{$v}%"));
        $query->when($filters['subject'] ?? null, fn($q, $v) => $q->where('subject', 'like', "%{$v}%"));
    }
}
