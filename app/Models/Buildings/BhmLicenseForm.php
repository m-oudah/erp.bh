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
        return $this->owner?->full_name ?? '';
    }

    public function getIdCardAttribute(): ?string {
        return $this->owner?->id_card;
    }

    public function getPhoneAttribute(): ?string {
        return $this->owner?->phone_number;
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['id_card'] ?? null, fn($q, $v) => $q->whereHas('owner', fn($sq) => $sq->where('id_card', $v)));
        $query->when($filters['name'] ?? null, fn($q, $v) => $q->whereHas('owner', fn($sq) => $sq->where(function($sub) use ($v) {
            $sub->where('first_name', 'like', "%{$v}%")
                ->orWhere('second_name', 'like', "%{$v}%")
                ->orWhere('third_name', 'like', "%{$v}%")
                ->orWhere('sur_name', 'like', "%{$v}%");
        })));
        $query->when($filters['status'] ?? null, fn($q, $v) => $q->where('status', $v));
        $query->when($filters['building_id'] ?? null, fn($q, $v) => $q->where('building_id', $v));
        $query->when($filters['building_number'] ?? null, fn($q, $v) => $q->where('building_number', 'like', "%{$v}%"));
        $query->when($filters['phone'] ?? null, fn($q, $v) => $q->whereHas('owner', fn($sq) => $sq->where('phone_number', 'like', "%{$v}%")));
        $query->when($filters['subject'] ?? null, fn($q, $v) => $q->where('subject', 'like', "%{$v}%"));
    }
}
