<?php

namespace App\Models;

use App\Enums\ProductVariantType;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductProductVariant extends Pivot
{
    public $table = 'product_product_variants';
    public $timestamps = false;
    protected $guarded = [];

    protected $casts = [
        'selection_type' => ProductVariantType::class
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
