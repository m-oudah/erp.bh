<?php namespace App\Models\Buildings; use Illuminate\Database\Eloquent\Model;
class BhmProofOfCase extends Model { protected $table='bhm_proof_of_cases'; protected $guarded=[]; public function building() { return $this->belongsTo(BhmBuilding::class,'building_id'); } }
