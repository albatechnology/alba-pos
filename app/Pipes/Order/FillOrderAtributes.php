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

        $order->user_id = $user->id;
        $order->company_id = $user->company->id;
        $order->tenant_id = $user->id;
        $order->note = $order->raw_source['note'] ?? null;
        $order->invoice_number = sprintf('INV%s', date('Ymdhis'));

        $order->status = OrderStatus::DONE;
        $order->payment_status = OrderPaymentStatus::SETTLEMENT;

        return $next($order);
    }
}
