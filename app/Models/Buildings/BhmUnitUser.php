<?php

namespace App\Models\Buildings;

use Illuminate\Database\Eloquent\Model;

class BhmUnitUser extends Model
{
    protected $table = 'bhm_unit_users';
    protected $guarded = [];
    public $timestamps = false;

    public function unit()
    {
        return $this->belongsTo(BhmUnit::class, 'unit_id');
    }

    public function getFullNameAttribute(): string
    {
        if ($this->name) {
            return $this->name;
        }
        $name = trim(collect([$this->first_name, $this->second_name, $this->third_name, $this->sur_name])->filter()->implode(' '));
        return $name ?: ($this->id_card || $this->id_no ? ('مواطن رقم ' . ($this->id_card ?: $this->id_no)) : 'غير محدد');
    }
}
