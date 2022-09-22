<?php

namespace App\Models;

use App\Interfaces\TenantedInterface;
use App\Traits\TenantedTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model implements TenantedInterface
{
    use SoftDeletes, TenantedTrait;
    public $table = 'order_details';

    protected $fillable = [
        'order_id',
        'tenant_id',
        'company_id',
        'product_id',
        'unit_price',
        'quantity',
        'total_discount',
        'total_price',
        'note',
    ];

    protected $casts = [
        'order_id' => 'integer',
        'tenant_id' => 'integer',
        'company_id' => 'integer',
        'product_id' => 'integer',
        'unit_price' => 'integer',
        'quantity' => 'integer',
        'total_discount' => 'integer',
        'total_price' => 'integer',
        'amount_paid' => 'integer',
    ];

    protected static function booted()
    {
        static::created(function ($model) {
            Stock::where('tenant_id', $model->tenant_id)->where('product_id', $model->product_id)->decrement('stock', $model->quantity);
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
