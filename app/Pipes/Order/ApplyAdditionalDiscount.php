<?php

namespace App\Pipes\Order;

use App\Models\Order;
use Closure;

class ApplyAdditionalDiscount
{
    public function handle(Order $order, Closure $next)
    {
        if (empty($order->raw_source['additional_discount']) && is_null($order->raw_source['additional_discount'])) return $next($order);

        $order->additional_discount = $order->raw_source['additional_discount'] ?? 0;
        $order->total_price -= $order->total_discount;

        return $next($order);
    }
}
