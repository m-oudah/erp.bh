<?php

namespace App\Models\Buildings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BhmCustomer extends Model
{
    use SoftDeletes;

    protected $table = 'bhm_customers';
    protected $guarded = [];
}
