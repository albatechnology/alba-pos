<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\PaymentType;
use App\Models\ProductCategory;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;

class CashierController extends Controller
{
    public function index()
    {
        $productCategories = ProductCategory::tenanted()->get();
        $cart = CartService::getMyCart() ?? new Cart();

        $paymentTypes = PaymentType::tenanted()->pluck('name', 'id')->prepend('- Select Payment -', '');;

        return view('cashiers.index', ['productCategories' => $productCategories, 'paymentTypes' => $paymentTypes, 'cart' => $cart]);
    }

    public function cart()
    {
        $cart = CartService::getMyCart()?->load('cartDetails');

        $sub_total_price = $cart?->total_price ?? 0;
        $total_tax = $cart?->cartDetails?->sum(function ($q) {
            return $q->product->tax * $q->quantity;
        }) ?? 0;
        $total_price = $sub_total_price + $total_tax;

        return view('cashiers.cart', ['cart' => $cart, 'sub_total_price' => $sub_total_price, 'total_tax' => $total_tax, 'total_price' => $total_price]);
    }

    public function proceedPayment(Request $request)
    {
        $cart = CartService::getMyCart();
        $data = $cart->cartDetails->map->only('product_id', 'quantity')->all();

        $raw_source = [
            'items' => $data,
            'additional_discount' => (int) $request->additional_discount ?? 0,
            'amount_paid' => (int) $request->amount_paid ?? 0,
            'payment_type_id' => $request->payment_type_id,
        ];
        // dump($request->all());
        if ($request->is_order) {
            $order = OrderService::processOrder(Order::make(['raw_source' => $raw_source]));
            if ($order) {
                return response()->json(['order_id' => $order->id]);
            }
        } else {
            $order = OrderService::previewOrder(Order::make(['raw_source' => $raw_source]));
        }

        $kembali = ($order->amount_paid - $order->total_price) ?? 0;
        return view('cashiers.proceedPayment', ['order' => $order, 'kembali' => $kembali]);
    }

    public function deleteCartDetail($cart_detail_id)
    {
        $cart = CartService::deleteDetail($cart_detail_id);
    }

    public function plusMinus($product_id, $qty)
    {
        $data = [
            ['product_id' => $product_id, 'quantity' => $qty]
        ];
        $cart = CartService::syncCart($data);
    }

    public function invoice(Order $order)
    {
        return view('cashiers.invoice', ['order' => $order]);
    }
}
