<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentTypeRequest;
use App\Http\Requests\UpdatePaymentTypeRequest;
use App\Http\Resources\PaymentTypeResource;
use App\Models\PaymentType;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PaymentTypeController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paymentTypes = PaymentType::simplePaginate();
        return PaymentTypeResource::collection($paymentTypes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePaymentTypeRequest $request)
    {
        $paymentType = PaymentType::create($request->validated());

        return PaymentTypeResource::collection($paymentType);

    }

    /**
     * Display the specified resource.
     *
     * @param  PaymentType  $paymentTypePaymentType
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentType $paymentTypePaymentType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  PaymentType  $paymentTypePaymentType
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePaymentTypeRequest $request, PaymentType $paymentType)
    {

        $paymentType->update($request->validated());

        return response()->json($paymentType);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  PaymentType  $paymentTypePaymentType
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentType $paymentType)
    {
        $paymentType->delete();
        return response()->json(['message' => 'Payment Type Deleted'], 200);
    }

    public function massDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:payment_Types,id',
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
