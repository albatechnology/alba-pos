<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartDetail extends Model
{
    public $table = 'cart_details';
    // protected $primaryKey = ['cart_id', 'product_id'];

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'total_price',
    ];

    protected $casts = [
        'cart_id' => 'integer',
        'product_id' => 'integer',
        'quantity' => 'integer',
        'total_price' => 'integer',
    ];

    protected static function booted()
    {
        static::saved(function ($model) {
            $model->cart->refreshTotalPrice();
        });

        static::deleted(function ($model) {
            $model->cart->refreshTotalPrice();
        });
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
