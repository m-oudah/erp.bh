<?php

namespace App\Models\Buildings;

use Illuminate\Database\Eloquent\Model;

class BhmUnitOwner extends Model
{
    protected $table = 'bhm_unit_owners';
    protected $guarded = [];
    public $timestamps = false;

    public function unit()
    {
        return $this->belongsTo(BhmUnit::class, 'unit_id');
    }

    public function buildingOwner()
    {
        return $this->belongsTo(BhmBuildingOwner::class, 'building_owner_id');
    }

    public function getFullNameAttribute(): string
    {
        $name = trim(collect([$this->first_name, $this->second_name, $this->third_name, $this->sur_name])->filter()->implode(' '));
        return $name ?: ($this->id_card ? ('مواطن رقم ' . $this->id_card) : 'غير محدد');
    }
}

