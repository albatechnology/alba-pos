<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Company;
use App\Models\Order;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:orders_access', ['only' => 'index']);
        $this->middleware('permission:orders_show', ['only' => 'show']);
        // $this->middleware('permission:orders_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:orders_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:orders_delete', ['only' => ['destroy', 'massDestroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Order::tenanted()->with(['company', 'tenant', 'user', 'customer'])->select(sprintf('%s.*', (new Order)->table));
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('placeholder', '&nbsp;')
                ->editColumn('created_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })
                ->editColumn('invoice_number', function ($row) {
                    return $row->invoice_number ?? '';
                })
                ->editColumn('payment_status', function ($row) {
                    return $row->payment_status->description ?? '';
                })
                ->addColumn('company_name', function ($row) {
                    return $row->company?->name ?? '';
                })
                ->addColumn('tenant_name', function ($row) {
                    return $row->tenant?->name ?? '';
                })
                ->addColumn('user_name', function ($row) {
                    return $row->user?->name ?? '';
                })
                ->addColumn('customer_name', function ($row) {
                    return $row->customer?->name ?? '';
                })
                ->addColumn('actions', function ($row) {
                    $extraActions  = '<a class="btn btn-warning btn-sm" href="' . url('cashier/invoice/' . $row->id) . '" target="_blank">Print Invoice</a>';
                    $extraActions  .= '<a class="btn btn-info btn-sm" href="' . url('orders/invoice/' . $row->id) . '">PDF</a>';
                    $viewGate      = 'orders_show';
                    $editGate      = 'orders_edit';
                    $deleteGate    = 'orders_delete';
                    $crudRoutePart = 'orders';
                    return view('layouts.includes.datatablesActions', compact('row', 'viewGate', 'editGate', 'deleteGate', 'crudRoutePart', 'extraActions'));
                })
                ->rawColumns(['placeholder', 'actions'])
                ->make(true);
        }
        return view('orders.index');
    }

    // public function create()
    // {
    //     $companies = Company::tenanted()->pluck('name', 'id')->prepend('- Select Company -', '');

    //     return view('orders.create', ['companies' => $companies]);
    // }

    public function store(StoreOrderRequest $request)
    {
        foreach (arrayFilterAndReindex($request->company_ids) as $company_id) {
            $data = $request->safe()->except(['company_ids']);
            $data['company_id'] = $company_id;
            Order::create($data);
        }
        alert()->success('Success', 'Data created successfully');
        return redirect('orders');
    }

    public function show(Order $order)
    {

        return view('orders.show', ['order' => $order]);
    }

    public function edit(Order $order)
    {
        $companies = Company::tenanted()->pluck('name', 'id')->prepend('- Select Company -', '');
        return view('orders.edit', ['Order' => $order, 'companies' => $companies]);
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order->update($request->validated());

        alert()->success('Success', 'Data updated successfully');
        return redirect('orders');
    }

    public function destroy(Order $order)
    {
        try {
            $order->delete();
        } catch (\Exception $e) {
            return $this->ajaxError($e->getMessage());
        }
        return $this->ajaxSuccess('Data deleted successfully');
    }

    public function massDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:orders,id',
        ]);

        Order::tenanted()->whereIn('id', $request->ids)->delete();
        alert()->success('Success', 'Data deleted successfully');
        return response(null, 204);
    }

    public function ajaxGetorders(Request $request)
    {
        if ($request->ajax()) {
            $orders = Order::tenanted();
            if ($request->company_id) {
                $company_id = explode(',', $request->company_id);
                $orders = $orders->whereIn('company_id', $company_id ?? []);
            }
            return $orders->get(['id', 'name']);
        }
    }

    public function invoice(Order $order)
    {
        $data['order'] = $order;
        $pdf = \PDF::loadView('orders.invoice', ['data' => $data])->setPaper('a4', 'potrait');
        return $pdf->download('invoice.pdf');
        // return view('orders.invoice', ['data' => $data]);
    }
}
