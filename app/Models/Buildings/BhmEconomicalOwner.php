<?php namespace App\Models\Buildings; use Illuminate\Database\Eloquent\Model;
class BhmEconomicalOwner extends Model { 
    protected $table='bhm_economical_owners'; 
    protected $guarded=[]; 

    public function getFullNameAttribute(): string {
        return trim("{$this->first_name} {$this->second_name} {$this->third_name} {$this->sur_name}");
    }
}
