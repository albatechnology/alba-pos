<?php

namespace App\Pipes\Order;

use App\Models\Cart;
use App\Models\Order;
use Closure;
use Illuminate\Support\Facades\DB;

class SaveOrder
{
    public function handle(Order $order, Closure $next)
    {
        $order = DB::transaction(function () use($order) {
            $orderDetails = $order->order_details;
            $orderShipment = $order->order_shipment;
            unset($order->request);
            unset($order->order_details);
            unset($order->order_shipment);
            $order->save();
            $order->details()->saveMany($orderDetails);
            $order->orderShipment()->save($orderShipment);

            Cart::myCart()->delete();
        });

        return $next($order);
    }
}
