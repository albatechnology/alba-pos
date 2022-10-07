<?php

namespace App\Pipes\Order;

use App\Enums\DiscountType;
use App\Models\Discount;
use App\Models\Order;
use Closure;

class ApplyDiscount
{
    public function handle(Order $order, Closure $next)
    {
        if (empty($order->raw_source['discount_id'])){
            $order->total_discount = 0;
            return $next($order);
        }

        $discount = Discount::where('id', $order->raw_source['discount_id'])->first();
        $order->discount_id = $discount->id;
        $order->total_discount = $discount->type=0? $discount->value : (($order->total_price * $discount->value) / 100);
        $order->total_price -= $order->total_discount;
        return $next($order);
    }
}
