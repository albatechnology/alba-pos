<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Company;
use App\Models\Payment;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PaymentController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:payments_access', ['only' => 'index']);
        $this->middleware('permission:payments_show', ['only' => 'show']);
        $this->middleware('permission:payments_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:payments_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:payments_delete', ['only' => ['destroy', 'massDestroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Payment::tenanted()->with(['company', 'tenant', 'addedBy', 'approvedBy'])->select(sprintf('%s.*', (new Payment)->table));
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('placeholder', '&nbsp;')
                ->editColumn('status', function ($row) {
                    return $row->status->description;
                })
                ->editColumn('created_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })
                ->addColumn('invoice_number', function ($row) {
                    return $row->order?->invoice_number ?? '';
                })
                ->addColumn('customer_name', function ($row) {
                    return $row->order?->customer?->name ?? '';
                })
                ->addColumn('company_name', function ($row) {
                    return $row->company?->name ?? '';
                })
                ->addColumn('tenant_name', function ($row) {
                    return $row->tenant?->name ?? '';
                })
                ->addColumn('added_by_name', function ($row) {
                    return $row->addedBy?->name ?? '';
                })
                ->addColumn('approved_by_name', function ($row) {
                    return $row->approvedBy?->name ?? '';
                })
                ->addColumn('actions', function ($row) {
                    // $viewGate      = 'payments_show';
                    // $editGate      = 'payments_edit';
                    // $deleteGate    = 'payments_delete';
                    $crudRoutePart = 'payments';
                    return view('layouts.includes.datatablesActions', compact('row', 'crudRoutePart'));
                })
                ->rawColumns(['placeholder', 'actions'])
                ->make(true);
        }
        return view('payments.index');
    }

    // public function create()
    // {
    //     $companies = Company::tenanted()->pluck('name', 'id')->prepend('- Select Company -', '');

    //     return view('payments.create', ['companies' => $companies]);
    // }

    // public function store(StorePaymentRequest $request)
    // {
    //     foreach (arrayFilterAndReindex($request->company_ids) as $company_id) {
    //         $data = $request->safe()->except(['company_ids']);
    //         $data['company_id'] = $company_id;
    //         Payment::create($data);
    //     }
    //     alert()->success('Success', 'Data created successfully');
    //     return redirect('payments');
    // }

    // public function edit(Payment $payment)
    // {
    //     $companies = Company::tenanted()->pluck('name', 'id')->prepend('- Select Company -', '');
    //     return view('payments.edit', ['Payment' => $payment, 'companies' => $companies]);
    // }

    // public function update(UpdatePaymentRequest $request, Payment $payment)
    // {
    //     $payment->update($request->validated());

    //     alert()->success('Success', 'Data updated successfully');
    //     return redirect('payments');
    // }

    // public function destroy(Payment $payment)
    // {
    //     try {
    //         $payment->delete();
    //     } catch (\Exception $e) {
    //         return $this->ajaxError($e->getMessage());
    //     }
    //     return $this->ajaxSuccess('Data deleted successfully');
    // }

    // public function massDestroy(Request $request)
    // {
    //     $request->validate([
    //         'ids'   => 'required|array',
    //         'ids.*' => 'exists:payments,id',
    //     ]);

    //     Payment::tenanted()->whereIn('id', $request->ids)->delete();
    //     alert()->success('Success', 'Data deleted successfully');
    //     return response(null, 204);
    // }

    public function ajaxGetPayments(Request $request)
    {
        if ($request->ajax()) {
            $payments = Payment::tenanted();
            if ($request->company_id) {
                $company_id = explode(',', $request->company_id);
                $payments = $payments->whereIn('company_id', $company_id ?? []);
            }
            return $payments->get(['id', 'name']);
        }
    }
}
