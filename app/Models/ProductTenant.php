<?php

namespace App\Models;

use App\Interfaces\TenantedInterface;
use App\Traits\TenantedTrait;
use Illuminate\Database\Eloquent\Model;

class ProductTenant extends Model implements TenantedInterface
{
    use TenantedTrait;

    public $table = 'product_tenants';
    protected $guarded = [];

    public function insertProductTenant(){

    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
