<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Stock;
use Exception;
use Illuminate\Support\Facades\DB;

class CartService
{
    /**
     * get user cart by active tenant
     *
     * @return Cart|null
     */
    public static function getMyCart()
    {
        $tenant = activeTenant();
        return Cart::myCart()->WhereTenantId($tenant->id)->first();
    }

    /**
     * Insert into carts table by single given data
     * example $data = [
     *          'product_id' => 1,
     *          'quantity' => 1,
     *      ];
     *
     */
    public static function store(array $data)
    {
        if (count($data) <= 0) throw new Exception('Cart data not found');

        $user = user();
        $tenant = activeTenant();

        if (!$tenant) throw new Exception('No tenant active selected');

        $stock = Stock::select('stock')->where('product_id', $data['product_id'])->where('tenant_id', $tenant->id)->first()?->stock ?? 0;
        if ($stock < 1) return;

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

    /**
     * synchronize carts by multiple given data
     * example $data = [
     *      [
     *          'product_id' => 1,
     *          'quantity' => 1,
     *      ],
     *      [
     *          'product_id' => 2,
     *          'quantity' => 1,
     *      ]
     * ]
     *
     */
    public static function syncCart(array $datas)
    {
        if (count($datas) <= 0) throw new Exception('Cart data not found');

        $user = user();
        $tenant = activeTenant();

        if (!$tenant) throw new Exception('No tenant active selected');

        $cart = Cart::firstOrCreate(
            [
                'user_id' => $user->id,
                'tenant_id' => $tenant->id,
            ]
        );

        foreach ($datas as $data) {
            DB::beginTransaction();
            try {
                $detail = $cart->cartDetails()->firstOrCreate(
                    [
                        'cart_id' => $cart->id,
                        'product_id' => $data['product_id']
                    ]
                );

                $product = DB::table('products')->select('price')->where('id', $data['product_id'])->whereNull('deleted_at')->first();

                $detail->quantity = $data['quantity'] ?? 1;
                $detail->total_price = $product->price * $detail->quantity;
                $detail->save();

                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
                throw $th;
            }
        }

        return $cart;
    }

    // public static function updateCart($request)
    // {
    //     $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);

    //     $request = $request->validated();
    //     for ($i = 0; $i < count($request['product_id']); $i++) {
    //         $item = $cart->items()->firstOrNew(
    //             [
    //                 'cart_id' => $cart->id,
    //                 'product_id' => $request['product_id'][$i],
    //             ]
    //         );

    //         $item->quantity = $request['quantity'][$i];
    //         $item->price = $request['product_price'][$i] * $request['quantity'][$i];
    //         $item->save();
    //     }

    //     return response()->json($cart->refresh());
    // }

    /**
     * Delete cart
     */
    // public static function delete($user_id = null, $tenant_id = null)
    public static function delete($cart_id)
    {
        Cart::destroy($cart_id);
        // if (is_null($user_id)) {
        //     $user_id = user()?->id ?? null;

        //     if (is_null($user_id)) return new Exception('User not found when deleting cart');
        // }

        // $cart = Cart::where('user_id', $user_id);
        // if (!is_null($tenant_id)) $cart = $cart->where('tenant_id', $tenant_id);
        // $cart->delete();
    }

    /**
     * Delete cart detail
     */
    // public static function deleteDetail($cart_id, $product_id = null)
    public static function deleteDetail($cart_detail_id)
    {
        CartDetail::destroy($cart_detail_id);
        // $cartDetail = CartDetail::where('cart_id', $cart_id);
        // if (!is_null($product_id)) $cartDetail = $cartDetail->where('product_id', $product_id);
        // $cartDetail->delete();
    }

    /**
     * Delete cart detail by product_id
     */
    public static function deleteDetailByProductId($product_id)
    {
        $user = user();
        $tenant = activeTenant();

        $cartDetail = $user->carts()
            ->where('tenant_id', $tenant->id)
            ->first()
            ->cartDetails?->filter(function (CartDetail $cartDetail) use ($product_id) {
                return $cartDetail->product_id === $product_id;
            })->first();

        if ($cartDetail) $cartDetail->delete();
    }
}
