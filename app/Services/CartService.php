<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartDetail;
use Exception;
use Illuminate\Support\Facades\DB;

class CartService
{
    public static function getMyCart()
    {
        $tenant = activeTenant();
        return Cart::myCart()->WhereTenantId($tenant->id)->first();
    }

    public static function store(array $data)
    {
        $user = user();
        $tenant = activeTenant();

        if (count($data) <= 0) throw new Exception('Cart data not found');
        if (!$tenant) throw new Exception('No tenant active selected');

        DB::beginTransaction();

        try {
            $cart = Cart::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'tenant_id' => $tenant->id,
                ]
            );

            $detail = $cart->cartDetails()->firstOrNew(
                [
                    'cart_id' => $cart->id,
                    'product_id' => $data['product_id']
                ]
            );

            $product = DB::table('products')->select('price')->where('id', $data['product_id'])->whereNull('deleted_at')->first();

            $detail->quantity = $detail->quantity + ($data['quantity'] ?? 1);
            $detail->total_price = $product->price * $detail->quantity;
            $detail->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return $cart;
    }

    public static function updateCart($request)
    {
        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);

        $request = $request->validated();
        for ($i = 0; $i < count($request['product_id']); $i++) {
            $item = $cart->items()->firstOrNew(
                [
                    'cart_id' => $cart->id,
                    'product_id' => $request['product_id'][$i],
                ]
            );

            $item->quantity = $request['quantity'][$i];
            $item->price = $request['product_price'][$i] * $request['quantity'][$i];
            $item->save();
        }

        return response()->json($cart->refresh());
    }

    public static function delete($cart_id)
    {
        Cart::destroy($cart_id);
    }

    public static function deleteDetail($cart_detail_id)
    {
        CartDetail::destroy($cart_detail_id);
    }

    public static function deleteDetailByProductId($product_id)
    {
        $user = user();
        $tenant = activeTenant();

        $cartDetail = $user->carts()
            ->where('tenant_id', $tenant->id)
            ->first()
            ->cartDetails
            ->filter(function (CartDetail $cartDetail) use ($product_id) {
                return $cartDetail->product_id === $product_id;
            })->first();

        if ($cartDetail) $cartDetail->delete();
    }
}
