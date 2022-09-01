<?php

namespace App\Models;

use App\Interfaces\TenantedInterface;
use App\Traits\TenantedTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model implements TenantedInterface
{
    use SoftDeletes, TenantedTrait;
    public $table = 'customers';
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
