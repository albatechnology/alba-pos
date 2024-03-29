<?php

namespace App\Http\Controllers;

use App\Enums\StockTypeEnum;
use App\Models\Cart;
use App\Models\Discount;
use App\Models\Order;
use App\Models\PaymentType;
use App\Models\ProductCategory;
use App\Models\Stock;
use App\Models\StockHistory;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\StockService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        if($cart->code){
            $stocks = StockHistory::where('user_id', $cart->id)->get();
            StockService::revertStock($stocks);
        }
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
        if (!$cart || $cart?->cartDetails?->count() <= 0) return response()->json(['success' => false]);

        $data = $cart->cartDetails->map->only('product_id', 'quantity')->all();

        $raw_source = [
            'items' => $data,
            'cart_id' => $cart->id,
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
                session()->forget('cart_code');
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

        if (!$cart || $cart?->cartDetails?->count() <= 0) return redirect('cashier');

        $discounts = Discount::tenanted()->where('is_active', 1)->whereDate('start_date', '<=', Carbon::now())->whereDate('end_date', '>=', Carbon::now())->pluck('name', 'id')->prepend('- Select Discount -', '');
        $paymentTypes = PaymentType::tenanted()->get();


        $sub_total_price = $cart?->cartDetails->sum('total_price') ?? 0;
        $total_tax = $cart?->cartDetails?->sum(function ($q) {
            return $q->product->tax * $q->quantity;
        }) ?? 0;
        $total_price = ($cart?->total_price ?? 0) + $total_tax;

        return view('cashiers.payment', ['paymentTypes' => $paymentTypes, 'discounts' => $discounts, 'cart' => $cart, 'total_price' => $total_price, 'sub_total_price' => $sub_total_price]);
    }

    public function saveCart(Request $request)
    {
        $cart = CartService::saveCart();
        if ($cart) {
            $stock = Stock::where('tenant_id',Auth::user()->tenant_id);
            foreach($cart->cartDetails as $product){
                $stock = $stock->where('product_id', $product->product_id)->first();
                $stock->stock = $stock->stock - $product->quantity;
                $stock->save();

                StockHistory::create(
                    [
                        'stock_id' => $stock->id,
                        'user_id' => $cart->id,
                        'type' => StockTypeEnum::DECREASE,
                        'changes' => $product->quantity,
                        'old_amount' => $stock->stock + $product->quantity,
                        'new_amount' => $stock->stock,
                        'source' => 'Cart'
                        ]
                    );

            }
            return response()->json(['success' => true, 'cart' => $cart]);
        }
        return response()->json(['success' => false]);
    }

    public function orderList()
    {
        $user = user();
        $carts = Cart::myCart()->WhereTenantId($user->tenant_id)->whereNotNull('code')->get();
        return view('cashiers.orderList', ['carts' => $carts]);
    }

    public function setOrder($code){
        $type = $_GET['type'] ?? 'order';
        session(['cart_code' => $code]);
        if($type == 'order') {
            return redirect('cashier');
        }
        return redirect('cashier/payment');
    }
}
