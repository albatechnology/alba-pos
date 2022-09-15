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

    protected $fillable = [
        'company_id',
        'product_brand_id',
        'code',
        'name',
        'uom',
        'price',
    ];

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

            /**
             * Create stock for product
             *
             * 1. check tenants of product company
             * 2. check stock by tenant_id and product_id. if not found create stock
             */

            $tenantIds = Tenant::where('company_id', $model->company_id)->pluck('id');
            if ($tenantIds->count() > 0) {
                $tenantIds->map(function ($tenantId) use ($model) {
                    Stock::firstOrCreate(
                        [
                            'tenant_id' => $tenantId,
                            'product_id' => $model->id
                        ],
                        [
                            'company_id' => $model->company_id,
                            'stock' => 0
                        ]
                    );
                });
            }
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function productBrand()
    {
        return $this->belongsTo(ProductBrand::class);
    }

    public function productCategories(){
        return $this->belongsToMany(ProductCategory::class, 'product_product_categories');
    }
}
