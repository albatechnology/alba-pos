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

        $company_id = $order->company_id;
        $tenant_id = $order->tenant_id;

        $orderDetails = collect($items)->map(function ($item) use ($company_id, $tenant_id) {
            $product = DB::table('products')->select('price', 'tax')->where('id', $item['product_id'])->first();

            $detail = new OrderDetail();
            $detail->company_id = $company_id;
            $detail->tenant_id = $tenant_id;
            $detail->product_id = $item['product_id'];
            $detail->unit_price = $product->price;
            $detail->quantity = (int)$item['quantity'];
            $detail->total_discount = 0;
            $detail->total_tax = $product->tax * $detail->quantity;
            $detail->original_price = $detail->unit_price * $detail->quantity;
            $detail->total_price = $detail->original_price + $detail->total_tax;
            return $detail;
        });

        $order->order_details = $orderDetails;
        $order->total_tax = $orderDetails->sum('total_tax');
        $order->original_price = $orderDetails->sum('original_price');
        $order->total_price = $orderDetails->sum('total_price');

        return $next($order);
    }
}
