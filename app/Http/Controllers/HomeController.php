<?php

namespace App\Http\Controllers;

use App\Helpers\PermissionsHelper;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function tess($array, $key)
    {
        $eek = collect($array)->map(function ($permission, $key) {
            if (is_array($permission)) {
                return collect($permission)->collapse()->prepend($key)->all();
                // return $this->tess($permission, $key);
            } else {
                return collect($permission)->collapse()->prepend($key)->all();
                // return collect($array)->collapse()->prepend($key)->all();
            }
        });
        return $eek;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $orderSummary = Order::tenanted()->whereOrderDeal()->selectRaw('SUM(total_price) as total_price')->first()->total_price ?? 0;
        $topProduct = Product::tenanted()->whereHas('orders', function ($q) {
            $q->whereOrderDeal();
        })->withSum('orderDetails', 'quantity')->orderBy('order_details_sum_quantity', 'desc')->limit(10)->get();


        // $topProduct = OrderDetail::tenanted()->whereHas('order', function($q){
        //     $q->whereOrderDeal();
        // })->selectRaw('product_id, SUM(quantity) as quantity')->groupBy('product_id')->Limit(10)->orderBy('quantity', 'DESC')->get();

        // dd($topProduct);
        return view('home', ['orderSummary' => $orderSummary, 'topProduct' => $topProduct]);
    }
}
