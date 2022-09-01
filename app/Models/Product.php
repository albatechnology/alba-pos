<?php

namespace App\Models;

use App\Interfaces\TenantedInterface;
use App\Traits\TenantedTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model implements TenantedInterface
{
    use SoftDeletes, TenantedTrait;
    public $table = 'products';
    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($model) {
            $tenants = Company::getTenantsCompany($model->company_id);
            if (isset($tenants) && count($tenants) > 0) {
                foreach ($tenants as $tenant) {
                    ProductTenant::create([
                        'tenant_id' => $tenant->id,
                        'product_id' => $model->id,
                    ]);
                }
            }
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // public function tenant()
    // {
    //     return $this->belongsTo(Tenant::class);
    // }
}
