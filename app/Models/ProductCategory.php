<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use SoftDeletes;
    public $table = 'product_categories';
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
