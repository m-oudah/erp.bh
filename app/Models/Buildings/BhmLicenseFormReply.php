<?php

namespace App\Models\Buildings;

use Illuminate\Database\Eloquent\Model;

class BhmLicenseFormReply extends Model
{
    protected $connection = 'bhm_old';
    protected $table = 'license_form_replies';
    protected $guarded = [];
}
