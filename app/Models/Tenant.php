<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use SoftDeletes;
    public $table = 'tenants';
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
