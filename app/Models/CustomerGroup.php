<?php

namespace App\Models;

use App\Interfaces\TenantedInterface;
use App\Traits\TenantedTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerGroup extends Model implements TenantedInterface
{
    use HasFactory, TenantedTrait;
    public $table = 'customer_groups';
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function customers(){
        return $this->belongsToMany(Customer::class, 'customer_customer_groups');
    }
}
