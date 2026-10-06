<?php
namespace App\Models\Buildings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BhmCustomerPen extends Model {
    use SoftDeletes;
    protected $table = 'bhm_customer_pens';
    protected $guarded = [];
    public function treatments() { return $this->belongsToMany(BhmTreatment::class, 'bhm_customer_pen_treatment', 'customer_id', 'treatment_id'); }
    public function getFullNameAttribute(): string {
        return trim(collect([$this->first_name, $this->second_name, $this->third_name, $this->sur_name])->filter()->implode(' ')) ?: ($this->name ?? '');
    }
}
