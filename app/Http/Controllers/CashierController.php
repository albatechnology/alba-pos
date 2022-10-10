<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Discount;
use App\Models\Order;
use App\Models\PaymentType;
use App\Models\ProductCategory;
use App\Services\CartService;
use App\Services\OrderService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CashierController extends Controller
{
    public function index()
    {
        if (!activeTenant()) {
            alert()->warning('Warning', 'Please choose a tenant');
            return redirect('/');
        }
        $productCategories = ProductCategory::tenanted()->get();
        $cart = CartService::getMyCart() ?? new Cart();
        $paymentTypes = PaymentType::tenanted()->pluck('name', 'id')->prepend('- Select Payment -', '');
        $discounts = Discount::tenanted()->where('is_active', 1)->whereDate('start_date', '<=', Carbon::now())->whereDate('end_date', '>=', Carbon::now())->pluck('name', 'id')->prepend('- Select Discount -', '');

        return view('cashiers.index', ['productCategories' => $productCategories, 'paymentTypes' => $paymentTypes, 'cart' => $cart, 'discounts' => $discounts]);
    }

    public function cart()
    {
        $cart = CartService::getMyCart()?->load('cartDetails');

        $sub_total_price = $cart?->cartDetails->sum('total_price') ?? 0;
        $total_tax = $cart?->cartDetails?->sum(function ($q) {
            return $q->product->tax * $q->quantity;
        }) ?? 0;
        $total_price = ($cart?->total_price ?? 0) + $total_tax;

        return view('cashiers.cart', ['cart' => $cart, 'sub_total_price' => $sub_total_price, 'total_tax' => $total_tax, 'total_price' => $total_price]);
    }

    public function proceedPayment(Request $request)
    {
        $cart = CartService::getMyCart();
        if(!$cart || $cart?->cartDetails?->count() <= 0) return response()->json(['success' => false]);

        $data = $cart->cartDetails->map->only('product_id', 'quantity')->all();

        $raw_source = [
            'items' => $data,
            'additional_discount' => (int) $request->additional_discount ?? 0,
            'amount_paid' => (int) $request->amount_paid ?? 0,
            'discount_id' => $request->discount_id,
            'payment_type_id' => $request->payment_type_id,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_email' => $request->customer_email,
            'customer_address' => $request->customer_address,
        ];
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

    public function setDiscount(Discount $discount)
    {
        $cart = CartService::getMyCart();
        if (!$cart) return response()->noContent();

        $cart->discount_id = $discount->id ?? null;
        $cart->save();
        $cart->refreshTotalPrice();
    }

    public function invoice(Order $order)
    {
        return view('cashiers.invoice', ['order' => $order]);
    }

    public function cartList()
    {
        $cart = CartService::getMyCart()?->load('cartDetails.product');

        $sub_total_price = $cart?->cartDetails->sum('total_price') ?? 0;
        $total_tax = $cart?->cartDetails?->sum(function ($q) {
            return $q->product->tax * $q->quantity;
        }) ?? 0;
        $total_price = ($cart?->total_price ?? 0) + $total_tax;

        return response()->json([
            'cart' => $cart,
            'sub_total_price' => $sub_total_price,
            'total_price' => $total_price,
        ]);
    }

    public function payment()
    {
        $cart = CartService::getMyCart()?->load('cartDetails.product');

        if(!$cart || $cart?->cartDetails?->count() <= 0) return redirect('cashier');

        $discounts = Discount::tenanted()->where('is_active', 1)->whereDate('start_date', '<=', Carbon::now())->whereDate('end_date', '>=', Carbon::now())->pluck('name', 'id')->prepend('- Select Discount -', '');
        $paymentTypes = PaymentType::tenanted()->get();


        $sub_total_price = $cart?->cartDetails->sum('total_price') ?? 0;
        $total_tax = $cart?->cartDetails?->sum(function ($q) {
            return $q->product->tax * $q->quantity;
        }) ?? 0;
        $total_price = ($cart?->total_price ?? 0) + $total_tax;

        return view('cashiers.payment', ['paymentTypes' => $paymentTypes, 'discounts' => $discounts, 'cart' => $cart, 'total_price' => $total_price, 'sub_total_price' => $sub_total_price]);
    }
}
