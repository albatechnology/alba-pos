<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public $table = 'carts';

    protected $fillable = [
        'user_id',
        'tenant_id',
        'discount_id',
        'total_price',
        'total_discount',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'tenant_id' => 'integer',
        'discount_id' => 'integer',
        'total_price' => 'integer',
        'total_discount' => 'integer',
    ];

    // protected $appends = ['total_items'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function cartDetails()
    {
        return $this->hasMany(CartDetail::class);
    }

    public function refreshTotalPrice()
    {
        $total_price = $this->cartDetails?->sum('total_price') ?? 0;
        $total_discount = 0;

        if ($this->discount_id) {
            $discount = Discount::find($this->discount_id);
            if ($discount) {
                if ($discount->type == 0) {
                    $total_discount = $discount->value;
                } else {
                    $total_discount = (($total_price * $discount->value) / 100);
                }
            }
        }

        $total_price = $total_price - $total_discount;

        $this->update([
            'total_price' => $total_price > 0 ? $total_price : 0,
            'total_discount' => $total_price > 0 ? ($total_discount ?? 0) : 0,
        ]);
    }

    // public function getTotalItemsAttribute()
    // {
    //     return $this->cartDetails->count();
    // }

    public function scopeMyCart($query)
    {
        return $query->where('user_id', user()->id);
    }

    public function scopeWhereTenantId($query, $tenant_id)
    {
        return $query->where('tenant_id', $tenant_id);
    }

    public function scopeMyCartHasDetails($query)
    {
        return $query->myCart()->has('cartDetails');
    }

    // public function calculateDiscount()
    // {
    //     if ($this->discount_id) {
    //         $discount = Discount::find($this->discount_id);
    //         $price = $discount->type = 0 ? $discount->value : (($this->total_price * $discount->value) / 100);
    //         $this->price = $price;
    //         $this->save();
    //     }
    // }
}
