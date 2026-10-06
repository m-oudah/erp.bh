<?php

namespace App\Models\Buildings;

use Illuminate\Database\Eloquent\Model;

class BhmCraftTypeEconomical extends Model
{
    protected $table = 'bhm_craft_type_economical';
    protected $guarded = [];

    public function economical()
    {
        return $this->belongsTo(BhmEconomical::class, 'economical_id');
    }

    public function craftType()
    {
        return $this->belongsTo(BhmCraftType::class, 'craft_type_id');
    }

    public function status()
    {
        return $this->belongsTo(BhmCraftStatus::class, 'craft_status_id');
    }

    public function category()
    {
        return $this->belongsTo(BhmCraftCategory::class, 'craft_category_id');
    }
}
