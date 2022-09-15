<?php

namespace App\Pipes\Order;

use App\Models\Order;
use App\Models\OrderDetail;
use Closure;
use Illuminate\Support\Facades\DB;

class MakeOrderDetails
{
    public function handle(Order $order, Closure $next)
    {
        $items = arrayFilterAndReindex($order->raw_source['items']);
        // dump($order);
        // $cart = Cart::myCartHasItems()->first();
        // if (!$cart) {
        //     throw new Exception("Cart not found");
        // }

        $orderDetails = collect($items)->map(function ($item) {
            $productPrice = DB::table('products')->where('id', $item['id'])->first('price')?->price ?? 0;

            $detail = new OrderDetail();
            $detail->product_id = $item['id'];
            $detail->unit_price = $productPrice;
            $detail->quantity = (int)$item['quantity'];
            $detail->total_discount = 0;
            $detail->total_price = $detail->unit_price * $detail->quantity;
            return $detail;
        });

        $order->order_details = $orderDetails;
        $order->total_price = $orderDetails->sum('total_price');

        return $next($order);
    }
}
