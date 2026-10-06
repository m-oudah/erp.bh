<?php namespace App\Models\Buildings; use Illuminate\Database\Eloquent\Model;
class BhmDepartment extends Model { protected $table='bhm_departments'; protected $guarded=[]; public function treatments() { return $this->hasMany(BhmTreatment::class,'department_id'); } }
