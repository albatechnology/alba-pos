<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentCategoryRequest;
use App\Http\Requests\UpdatePaymentCategoryRequest;
use App\Models\Company;
use App\Models\PaymentCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PaymentCategoryController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:payment_categories_access', ['only' => 'index']);
        $this->middleware('permission:payment_categories_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:payment_categories_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:payment_categories_delete', ['only' => ['destroy', 'massDestroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PaymentCategory::tenanted()->with('company')->select(sprintf('%s.*', (new PaymentCategory)->table));
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('placeholder', '&nbsp;')
                ->editColumn('created_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })
                ->editColumn('updated_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->updated_at));
                })
                ->addColumn('company_name', function ($row) {
                    return $row->company?->name ?? '';
                })
                ->addColumn('actions', function ($row) {
                    $editGate      = 'payment_categories_edit';
                    $deleteGate    = 'payment_categories_delete';
                    $crudRoutePart = 'payment-categories';
                    return view('layouts.includes.datatablesActions', compact('row', 'editGate', 'deleteGate', 'crudRoutePart'));
                })
                ->rawColumns(['placeholder', 'actions'])
                ->make(true);
        }
        return view('paymentCategories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::tenanted()->pluck('name', 'id')->prepend('- Select Company-', '');

        return view('paymentCategories.create', ['companies' => $companies]);
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
            PaymentCategory::create($data);
        }
        alert()->success('Success', 'Data created successfully');
        return redirect('payment-categories');
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
     * Show the form for editing the specified resource.
     *
     * @param  PaymentCategory  $paymentCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentCategory $paymentCategory)
    {
        $companies = Company::tenanted()->pluck('name', 'id')->prepend('- Select Company-', '');
        return view('paymentCategories.edit', ['paymentCategory' => $paymentCategory, 'companies' => $companies]);
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
        $paymentCategory->update($request->validated());

        alert()->success('Success', 'Data updated successfully');
        return redirect('payment-categories');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  PaymentCategory  $paymentCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentCategory $paymentCategory)
    {
        try {
            $paymentCategory->delete();
        } catch (\Exception $e) {
            return $this->ajaxError($e->getMessage());
        }
        return $this->ajaxSuccess('Data deleted successfully');
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
