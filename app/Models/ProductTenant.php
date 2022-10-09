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

    public function scopeTenanted($query)
    {
        $hasActiveTenant = tenancy()->getActiveTenant();
        if ($hasActiveTenant) return $query->tenantedActiveTenant($hasActiveTenant);

        $user = user();
        return $user->is_super_admin ? $query : $query->whereIn('tenant_id', tenancy()->getTenants()->pluck('id'));
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function stocks(){
        return $this->hasMany(Stock::class, 'product_id', 'product_id');
    }

    public function stock(){
        return $this->stocks()->where('tenant_id', activeTenant()->id);
    }

    public function scopeWhereProductId($query, $id)
    {
        return $query->where('product_id', $id);
    }
}
