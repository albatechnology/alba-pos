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
        if (empty($order->request['discount_code'])) return $next($order);

        $discount = Discount::where('activation_code', $order->request['discount_code'])->first();
        $order->discount_id = $discount->id;
        $order->total_discount = $discount->type->is(DiscountType::NOMINAL) ? $discount->value : (($order->total_price * $discount->value) / 100);
        $order->total_price -= $order->total_discount;

        return $next($order);
    }
}
