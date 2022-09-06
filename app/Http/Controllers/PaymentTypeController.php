<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentTypeRequest;
use App\Http\Requests\UpdatePaymentTypeRequest;
use App\Models\PaymentCategory;
use App\Models\PaymentType;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PaymentTypeController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:payment_types_access', ['only' => 'index']);
        $this->middleware('permission:payment_types_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:payment_types_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:payment_types_delete', ['only' => ['destroy', 'massDestroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PaymentType::tenanted()->with(['company', 'paymentCategory'])->select(sprintf('%s.*', (new PaymentType)->table));
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('placeholder', '&nbsp;')
                ->editColumn('created_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })
                ->editColumn('updated_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->updated_at));
                })
                ->addColumn('payment_category', function ($row) {
                    return $row->paymentCategory?->name ?? '-';
                })
                ->addColumn('company_name', function ($row) {
                    return $row->company?->name ?? '-';
                })
                ->addColumn('actions', function ($row) {
                    $editGate      = 'payment_types_edit';
                    $deleteGate    = 'payment_types_delete';
                    $crudRoutePart = 'payment-types';
                    return view('layouts.includes.datatablesActions', compact('row', 'editGate', 'deleteGate', 'crudRoutePart'));
                })
                ->rawColumns(['placeholder', 'actions'])
                ->make(true);
        }
        return view('paymentTypes.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $paymentCategories = PaymentCategory::tenanted()->pluck('name', 'id')->prepend('- Select Category -', '');

        return view('paymentTypes.create', ['paymentCategories' => $paymentCategories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StorePaymentTypeRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePaymentTypeRequest $request)
    {
        PaymentType::create($request->validated());
        alert()->success('Success', 'Data created successfully');
        return redirect('payment-types');
    }

    /**
     * Display the specified resource.
     *
     * @param  PaymentType  $paymentType
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentType $paymentType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  PaymentType  $paymentType
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentType $paymentType)
    {
        $paymentCategories = PaymentCategory::tenanted()->pluck('name', 'id')->prepend('- Select Category -', '');
        return view('paymentTypes.edit', ['paymentType' => $paymentType, 'paymentCategories' => $paymentCategories]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdatePaymentTypeRequest $request
     * @param  PaymentType  $paymentType
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePaymentTypeRequest $request, PaymentType $paymentType)
    {
        $paymentType->update($request->validated());

        alert()->success('Success', 'Data updated successfully');
        return redirect('payment-types');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  PaymentType  $paymentType
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentType $paymentType)
    {
        try {
            $paymentType->delete();
        } catch (\Exception $e) {
            return $this->ajaxError($e->getMessage());
        }
        return $this->ajaxSuccess('Data deleted successfully');
    }

    public function massDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:payment_types,id',
        ]);

        PaymentType::tenanted()->whereIn('id', $request->ids)->delete();
        alert()->success('Success', 'Data deleted successfully');
        return response(null, 204);
    }

    public function ajaxGetPaymentTypes(Request $request)
    {
        if ($request->ajax()) {
            $paymentTypes = PaymentType::tenanted();
            if ($request->company_id) {
                $company_id = explode(',', $request->company_id);
                $paymentTypes = $paymentTypes->whereIn('company_id', $company_id ?? []);
            }
            return $paymentTypes->get(['id', 'name']);
        }
    }
}
