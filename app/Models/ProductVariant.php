<?php

namespace App\Models;

use App\Interfaces\TenantedInterface;
use App\Traits\TenantedTrait;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model implements TenantedInterface
{
    use TenantedTrait;
    public $table = 'product_variants';
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function products(){
        return $this->belongsToMany(Product::class, 'product_product_variants', 'product_variant_id', 'product_id')->withPivot('selection_type');
    }

    public function productVariantItems(){
        return $this->hasMany(ProductVariantItem::class);
    }

    public function scopeWhereProductId($query, $id)
    {
        return $query->whereHas('products', fn($q)=>$q->where('product_id', $id));
    }
}
