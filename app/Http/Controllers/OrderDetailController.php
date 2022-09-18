<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderDetailRequest;
use App\Http\Requests\UpdateOrderDetailRequest;
use App\Models\Company;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OrderDetailController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:order_details_access', ['only' => 'index']);
        $this->middleware('permission:order_details_show', ['only' => 'show']);
        // $this->middleware('permission:order_details_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:order_details_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:order_details_delete', ['only' => ['destroy', 'massDestroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = OrderDetail::tenanted()->with(['company', 'tenant', 'product'])->select(sprintf('%s.*', (new OrderDetail)->table));
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('placeholder', '&nbsp;')
                ->editColumn('created_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })
                ->addColumn('invoice_number', function ($row) {
                    return $row->order?->invoice_number ?? '';
                })
                ->addColumn('company_name', function ($row) {
                    return $row->company?->name ?? '';
                })
                ->addColumn('tenant_name', function ($row) {
                    return $row->tenant?->name ?? '';
                })
                ->addColumn('product_name', function ($row) {
                    return $row->product?->name ?? '';
                })
                ->addColumn('actions', function ($row) {
                    $viewGate      = 'order_details_show';
                    $editGate      = 'order_details_edit';
                    $deleteGate    = 'order_details_delete';
                    $crudRoutePart = 'order-details';
                    return view('layouts.includes.datatablesActions', compact('row', 'viewGate', 'editGate', 'deleteGate', 'crudRoutePart'));
                })
                ->rawColumns(['placeholder', 'actions'])
                ->make(true);
        }
        return view('orderDetails.index');
    }

    // public function create()
    // {
    //     $companies = Company::tenanted()->pluck('name', 'id')->prepend('- Select Company -', '');

    //     return view('orderDetails.create', ['companies' => $companies]);
    // }

    public function store(StoreOrderDetailRequest $request)
    {
        foreach (arrayFilterAndReindex($request->company_ids) as $company_id) {
            $data = $request->safe()->except(['company_ids']);
            $data['company_id'] = $company_id;
            OrderDetail::create($data);
        }
        alert()->success('Success', 'Data created successfully');
        return redirect('order-details');
    }

    public function edit(OrderDetail $orderDetail)
    {
        $companies = Company::tenanted()->pluck('name', 'id')->prepend('- Select Company -', '');
        return view('orderDetails.edit', ['OrderDetail' => $orderDetail, 'companies' => $companies]);
    }

    public function update(UpdateOrderDetailRequest $request, OrderDetail $orderDetail)
    {
        $orderDetail->update($request->validated());

        alert()->success('Success', 'Data updated successfully');
        return redirect('order-details');
    }

    public function destroy(OrderDetail $orderDetail)
    {
        try {
            $orderDetail->delete();
        } catch (\Exception $e) {
            return $this->ajaxError($e->getMessage());
        }
        return $this->ajaxSuccess('Data deleted successfully');
    }

    public function massDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:order_details,id',
        ]);

        OrderDetail::tenanted()->whereIn('id', $request->ids)->delete();
        alert()->success('Success', 'Data deleted successfully');
        return response(null, 204);
    }

    public function ajaxGetOrderDetails(Request $request)
    {
        if ($request->ajax()) {
            $orderDetails = OrderDetail::tenanted();
            if ($request->company_id) {
                $company_id = explode(',', $request->company_id);
                $orderDetails = $orderDetails->whereIn('company_id', $company_id ?? []);
            }
            return $orderDetails->get(['id', 'name']);
        }
    }
}
