<?php

namespace App\Pipes\Order;

use App\Models\Order;
use Closure;

class ProcessAmountPaid
{
    public function handle(Order $order, Closure $next)
    {
        if (empty($order->raw_source['amount_paid']) && is_null($order->raw_source['amount_paid'])) return $next($order);

        $order->amount_paid = (int) $order->raw_source['amount_paid'] ?? 0;

        return $next($order);
    }
}
