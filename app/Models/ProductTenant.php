<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTenant extends Model
{
    public $table = 'product_tenants';
    protected $guarded = [];

    public function insertProductTenant(){

    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
