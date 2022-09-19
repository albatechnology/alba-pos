<?php

namespace App\Pipes\Order;

use App\Models\Order;
use Closure;

class SetPaymentType
{
    public function handle(Order $order, Closure $next)
    {
        if (empty($order->raw_source['payment_type_id']) && is_null($order->raw_source['payment_type_id'])) return $next($order);

        $order->payment_type_id = (int)$order->raw_source['payment_type_id'];

        return $next($order);
    }
}
