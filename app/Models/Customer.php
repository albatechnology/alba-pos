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
    protected $fillable = [
        'company_id',
        'tenant_id',
        'name',
        'email',
        'phone',
        'address',
        'description',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function customerGroups()
    {
        return $this->belongsToMany(CustomerGroup::class, 'customer_customer_groups');
    }
}
