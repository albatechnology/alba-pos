<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public $table = 'carts';

    protected $fillable = [
        'user_id',
        'tenant_id',
        'total_price',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'tenant_id' => 'integer',
        'total_price' => 'integer',
    ];

    // protected $appends = ['total_items'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cartDetails()
    {
        return $this->hasMany(CartDetail::class);
    }

    public function refreshTotalPrice()
    {
        $total_price = $this->cartDetails->sum('total_price');
        $this->update(['total_price' => $total_price ?? 0]);
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
}
