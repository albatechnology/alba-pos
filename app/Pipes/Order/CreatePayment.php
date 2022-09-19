<?php

namespace App\Pipes\Order;

use App\Models\Order;
use Closure;

class CreatePayment
{
    public function handle(Order $order, Closure $next)
    {
        $order->invoice_number = sprintf('INV%s', date('Ymdhis'));
        $order->save();

        return $next($order);
    }
}
