<?php

namespace App\Models;

use App\Interfaces\TenantedInterface;
use App\Traits\TenantedTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model implements TenantedInterface
{
    use SoftDeletes, TenantedTrait;
    public $table = 'product_categories';
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function products(){
        return $this->belongsToMany(ProductCategory::class, 'product_product_categories');
    }
}
