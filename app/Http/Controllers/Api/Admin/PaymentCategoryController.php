<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentCategoryRequest;
use App\Http\Requests\UpdatePaymentCategoryRequest;
use App\Http\Resources\PaymentCategoryResource;
use App\Models\Company;
use App\Models\PaymentCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PaymentCategoryController extends Controller
{
    // function __construct()
    // {
    //     $this->middleware('permission:payment_categories_access', ['only' => 'index']);
    //     $this->middleware('permission:payment_categories_create', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:payment_categories_edit', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:payment_categories_delete', ['only' => ['destroy', 'massDestroy']]);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paymentCategories = PaymentCategory::simplePaginate();
        return PaymentCategoryResource::collection($paymentCategories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePaymentCategoryRequest $request)
    {
        foreach (arrayFilterAndReindex($request->company_ids) as $company_id) {
            $data = $request->safe()->except(['company_ids']);
            $data['company_id'] = $company_id;
            $paymentCategory = PaymentCategory::create($data);
        }

        return response()->json($paymentCategory, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  PaymentCategory  $paymentCategory
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentCategory $paymentCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  PaymentCategory  $paymentCategory
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePaymentCategoryRequest $request, PaymentCategory $paymentCategory)
    {
        $data = array_merge($request->validated(), ['is_exact_change' => $request->is_exact_change ?? 0]);
        $paymentCategory->update($data);

        return response()->json($paymentCategory);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  PaymentCategory  $paymentCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentCategory $paymentCategory)
    {
        $paymentCategory->delete();
        return response()->json(['message' => 'Payment Category Deleted'], 200);
    }

    public function massDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:payment_categories,id',
        ]);

        PaymentCategory::tenanted()->whereIn('id', $request->ids)->delete();
        alert()->success('Success', 'Data deleted successfully');
        return response(null, 204);
    }

    public function ajaxGetPaymentCategories(Request $request)
    {
        if ($request->ajax()) {
            $paymentCategories = PaymentCategory::tenanted();
            if ($request->company_id) {
                $company_id = explode(',', $request->company_id);
                $paymentCategories = $paymentCategories->whereIn('company_id', $company_id ?? []);
            }
            return $paymentCategories->get(['id', 'name']);
        }
    }
}
