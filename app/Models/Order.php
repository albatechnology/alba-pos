<?php

namespace App\Models;

use App\Enums\OrderPaymentStatus;
use App\Enums\OrderStatus;
use App\Interfaces\TenantedInterface;
use App\Traits\TenantedTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model implements TenantedInterface
{
    use SoftDeletes, TenantedTrait;
    public $table = 'orders';

    protected $fillable = [
        'raw_source',
        'tenant_id',
        'company_id',
        'user_id',
        'customer_id',
        'discount_id',
        'invoice_number',
        'status',
        'payment_status',
        'total_discount',
        'additional_discount',
        'amount_paid',
        'total_tax',
        'total_price',
        'note',
    ];

    protected $casts = [
        'tenant_id' => 'integer',
        'company_id' => 'integer',
        'user_id' => 'integer',
        'customer_id' => 'integer',
        'discount_id' => 'integer',
        'status' => OrderStatus::class,
        'payment_status' => OrderPaymentStatus::class,
        'total_discount' => 'integer',
        'total_price' => 'integer',
        'amount_paid' => 'integer',
    ];

    protected static function booted()
    {
        static::created(function ($model) {
            // $model->orderDetails->each(function ($orderDetail) use ($model) {
            //     Stock::where('tenant_id', $model->tenant_id)->where('product_id', $orderDetail->product_id)->decrement('stock', $orderDetail->quantity);
            // });

            // foreach ($model->orderDetails as $orderDetail) {
            //     Stock::where('tenant_id', $model->tenant_id)->where('product_id', $orderDetail->product_id)->decrement('stock', $orderDetail->quantity);
            // }
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

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function scopeWhereOrderDeal($query)
    {
        return $query->whereIn('status', [OrderStatus::SHIPMENT, OrderStatus::DELIVERING, OrderStatus::ARRIVED, OrderStatus::DONE]);
    }
}
