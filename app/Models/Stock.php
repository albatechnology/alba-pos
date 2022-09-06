<?php

namespace App\Models;

use App\Interfaces\TenantedInterface;
use App\Traits\TenantedTrait;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model implements TenantedInterface
{
    use TenantedTrait;
    public $table = 'stocks';
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
