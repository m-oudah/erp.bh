<?php
namespace App\Models\Buildings;
use Illuminate\Database\Eloquent\Model;

class BhmTreatment extends Model {
    protected $table = 'bhm_treatments';
    protected $guarded = [];
    public function department() { return $this->belongsTo(BhmDepartment::class, 'department_id'); }
    public function replies() { return $this->hasMany(BhmTreatmentReply::class, 'treatment_id'); }
    public function customerPens() { return $this->belongsToMany(BhmCustomerPen::class, 'bhm_customer_pen_treatment', 'treatment_id', 'customer_id'); }
    public function economical() { return $this->belongsTo(BhmEconomical::class, 'economical_id'); }
    public function creator() { return $this->belongsTo(\App\Models\User::class, 'created_by'); }
}
