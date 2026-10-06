<?php namespace App\Models\Buildings; use Illuminate\Database\Eloquent\Model;
class BhmBuildFinancial extends Model { protected $table='bhm_build_financial'; protected $guarded=[]; public function building() { return $this->belongsTo(BhmBuilding::class,'building_id'); } }
