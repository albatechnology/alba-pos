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
            $product = DB::table('products')->select('price', 'tax')->where('id', $item['product_id'])->first();

            $detail = new OrderDetail();
            $detail->product_id = $item['product_id'];
            $detail->unit_price = $product->price;
            $detail->quantity = (int)$item['quantity'];
            $detail->total_discount = 0;
            $detail->total_price = $detail->unit_price * $detail->quantity;
            $detail->total_tax = $product->tax * $detail->quantity;
            return $detail;
        });

        $order->order_details = $orderDetails;
        $order->total_tax = $orderDetails->sum('total_tax');
        $order->total_price = $orderDetails->sum('total_price');

        return $next($order);
    }
}
