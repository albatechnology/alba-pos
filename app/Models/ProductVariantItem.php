<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantItem extends Model
{
    public $table = 'product_variant_items';
    protected $guarded = [];

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
