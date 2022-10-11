<?php

namespace App\Pipes\Order;

use App\Enums\OrderPaymentStatus;
use App\Enums\OrderStatus;
use App\Models\Order;
use Closure;

class FillOrderAtributes
{
    public function handle(Order $order, Closure $next)
    {
        $user = user();

        $order->cart_id = $order->raw_source['cart_id'] ?? null;
        $order->user_id = $user->id;
        $order->company_id = $user->company_id;
        $order->tenant_id = $user->tenant_id;
        $order->note = $order->raw_source['note'] ?? null;
        $order->invoice_number = sprintf('INV%s', date('Ymdhis'));

        $order->status = OrderStatus::DONE;
        $order->payment_status = OrderPaymentStatus::SETTLEMENT;
        return $next($order);
    }
}
