<?php

namespace App\Pipes\Order;

use App\Models\Order;
use Closure;

class FillOrderAtributes
{
    public function handle(Order $order, Closure $next)
    {
        $user = user();

        $order->user_id = $user->id;
        $order->note = $order->raw_source['note'] ?? null;

        return $next($order);
    }
}
