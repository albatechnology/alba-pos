<?php

namespace App\Models;

use App\Interfaces\TenantedInterface;
use App\Traits\TenantedTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements TenantedInterface, HasMedia
{
    use SoftDeletes, TenantedTrait, InteractsWithMedia;
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
                        'uom' => $model->uom,
                        'price' => $model->price,
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

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('products')
            ->useFallbackUrl('/https://www.tibs.org.tw/images/default.jpg')
            ->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(368)
            ->height(232)
            ->sharpen(10);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function stock()
    {
        return $this->hasOne(Stock::class)->where('tenant_id', activeTenant()->id);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function productBrand()
    {
        return $this->belongsTo(ProductBrand::class);
    }

    public function productCategories()
    {
        return $this->belongsToMany(ProductCategory::class, 'product_product_categories');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_details');
    }
}
