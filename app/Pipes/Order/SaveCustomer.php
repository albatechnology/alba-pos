<?php

namespace App\Pipes\Order;

use App\Models\Order;
use App\Models\Customer;
use Closure;

class SaveCustomer
{
    public function handle(Order $order, Closure $next)
    {
        if (empty($order->raw_source['customer_phone'])) return $next($order);

        $customer = Customer::firstorCreate(
            [
                'company_id' =>  $order->company_id,
                'tenant_id' => $order->tenant_id,
                'phone' => $order->raw_source['customer_phone'],
                'email' => $order->raw_source['customer_email'],
            ],
            [
                'name' => $order->raw_source['customer_name'],
            ]
        );
        $order->customer_id = $customer->id;

        return $next($order);
    }
}
