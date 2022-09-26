<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;

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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $orderSummary = Order::tenanted()->whereOrderDeal()->selectRaw('SUM(total_price) as total_price')->first()->total_price ?? 0;
        $topProduct = Product::tenanted()->withSum(['orderDetails'=>function($q){
             $q->whereHas('order', function($q){
                $q->whereOrderDeal();
            });
            $q->orderBy('quantity', 'DESC');
        }],'quantity')->limit(10)->get();

        // $topProduct = OrderDetail::tenanted()->whereHas('order', function($q){
        //     $q->whereOrderDeal();
        // })->selectRaw('product_id, SUM(quantity) as quantity')->groupBy('product_id')->Limit(10)->orderBy('quantity', 'DESC')->get();

        // dd($topProduct);
        return view('home', ['orderSummary' => $orderSummary, 'topProduct' => $topProduct]);
    }
}
