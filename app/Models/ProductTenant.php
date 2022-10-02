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

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeWhereProductId($query, $id)
    {
        return $query->where('product_id', $id);
    }
}
