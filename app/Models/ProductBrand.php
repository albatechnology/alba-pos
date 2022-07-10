<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductBrand extends Model
{
    use SoftDeletes;
    public $table = 'product_brands';
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
