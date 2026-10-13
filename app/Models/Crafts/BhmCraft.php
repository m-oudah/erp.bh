<?php

namespace App\Models\Crafts;

use Illuminate\Database\Eloquent\Model;

class BhmCraft extends Model
{
    protected $table = 'bhm_crafts';
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(\App\Models\Buildings\BhmCustomer::class, 'customer_id');
    }

    public function statusRecord()
    {
        return $this->belongsTo(\App\Models\Buildings\BhmCraftStatus::class, 'status');
    }

    public function craftType()
    {
        return $this->belongsTo(\App\Models\Buildings\BhmCraftType::class, 'craft_id');
    }
}
