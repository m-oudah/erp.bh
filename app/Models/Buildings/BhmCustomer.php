<?php

namespace App\Models\Buildings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BhmCustomer extends Model
{
    use SoftDeletes;

    protected $table = 'bhm_customers';
    protected $guarded = [];

    public function getFullNameAttribute()
    {
        return $this->name;
    }

    public function buildingOwners()
    {
        return $this->hasMany(BhmBuildingOwner::class, 'id_card', 'id_no');
    }

    public function crafts()
    {
        return $this->hasMany(\App\Models\Crafts\BhmCraft::class, 'customer_id');
    }

    public function transactions()
    {
        return $this->hasMany(\App\Models\Transactions\BhmTransaction::class, 'customer_id');
    }

    public function draftTransactions()
    {
        return $this->hasMany(\App\Models\Transactions\BhmDraftTransaction::class, 'national_id', 'id_no');
    }
}
