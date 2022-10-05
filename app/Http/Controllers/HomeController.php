<?php

namespace App\Http\Controllers;

use App\Helpers\PermissionsHelper;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

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
    public function index(Request $request)
    {
        $startDate = $request->start_date ?? date('Y-m-d');
        $endDate = $request->end_date ?? date('Y-m-d');
        $orderSummary = Order::tenanted()->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->whereOrderDeal()->selectRaw('SUM(total_price) as total_price')->first()->total_price ?? 0;

        $topProduct = Product::tenanted()->whereHas('orders', function ($q) use ($startDate, $endDate) {
            $q->whereDate('orders.created_at', '>=', $startDate)->whereDate('orders.created_at', '<=', $endDate)->whereOrderDeal();
        })->withSum('orderDetails', 'quantity')->orderBy('order_details_sum_quantity', 'desc')->limit(10)->get();


        // $topProduct = OrderDetail::tenanted()->whereHas('order', function($q){
        //     $q->whereOrderDeal();
        // })->selectRaw('product_id, SUM(quantity) as quantity')->groupBy('product_id')->Limit(10)->orderBy('quantity', 'DESC')->get();

        // dd($topProduct);
        return view('home', ['orderSummary' => $orderSummary, 'topProduct' => $topProduct, 'startDate' => $startDate, 'endDate' => $endDate]);
    }

    public function productReport(Request $request)
    {
        // $startDate = $request->start_date ?? date('Y-m-d');
        // $endDate = $request->end_date ?? date('Y-m-d');
        // $productReport = Product::tenanted()->whereHas('orders', function($q) use($startDate, $endDate){
        //     $q->whereDate('orders.created_at','>=', $startDate)->whereDate('orders.created_at','<=', $endDate)->whereOrderDeal();
        // })->withSum('orderDetails', 'quantity')->orderBy('order_details_sum_quantity', 'desc')->get();

        // return view('report', ['productReport' => $productReport, 'startDate' => $startDate, 'endDate' => $endDate]);

        $tenant = activeTenant();
        $company = $tenant ? $tenant->company : activeCompany();

        $startDate = $request->start_date ?? date('Y-m-d');
        $endDate = $request->end_date ?? date('Y-m-d');
        if ($request->ajax()) {

            $productReport = Product::tenanted()->whereHas('orders', function ($q) use ($startDate, $endDate) {
                $q->whereDate('orders.created_at', '>=', $startDate)->whereDate('orders.created_at', '<=', $endDate)->whereOrderDeal();
            })->withSum('orderDetails', 'quantity')->orderBy('order_details_sum_quantity', 'desc');

            return DataTables::of($productReport)->addIndexColumn()
                ->addColumn('placeholder', '')
                ->addColumn('total', function ($row) {
                    return number_format($row->order_details_sum_quantity * $row->price);
                })
                ->editColumn('price', function ($row) {
                    return number_format($row->price);
                })
                ->make(true);
        }
        return view('report', ['startDate' => $startDate, 'endDate' => $endDate, 'tenant' => $tenant, 'company' => $company]);
    }
}
