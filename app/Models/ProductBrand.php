<?php

namespace App\Models;

use App\Interfaces\TenantedInterface;
use App\Traits\CompanyTenantedTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductBrand extends Model implements TenantedInterface
{
    use SoftDeletes, CompanyTenantedTrait;
    public $table = 'product_brands';
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
