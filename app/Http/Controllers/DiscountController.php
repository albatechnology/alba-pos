<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDiscountRequest;
use App\Http\Requests\UpdateDiscountRequest;
use App\Models\Company;
use App\Models\Discount;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DiscountController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:discounts_access', ['only' => 'index']);
        $this->middleware('permission:discounts_view', ['only' => 'show']);
        $this->middleware('permission:discounts_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:discounts_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:discounts_delete', ['only' => ['destroy', 'massDestroy']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Discount::tenanted()->with('company')->select(sprintf('%s.*', (new Discount())->table));
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('placeholder', '&nbsp;')
                ->addColumn('company_name', function ($row) {
                    return $row->company?->name ?? '';
                })
                ->editColumn('type', function ($row) {
                    return $row->type == 0 ? 'Nominal' : 'Percentage';
                })
                ->editColumn('is_active', function ($row) {
                    return $row->is_active == 1 ? 'Active' : 'Inactive';
                })
                ->editColumn('created_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })
                ->editColumn('updated_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->updated_at));
                })
                ->addColumn('actions', function ($row) {
                    $viewGate      = 'discounts_show';
                    $editGate      = 'discounts_edit';
                    $deleteGate    = 'discounts_delete';
                    $crudRoutePart = 'discounts';
                    return view('layouts.includes.datatablesActions', compact('row', 'viewGate', 'editGate', 'deleteGate', 'crudRoutePart'));
                })
                ->rawColumns(['placeholder', 'actions'])
                ->make(true);
        }
        return view('discounts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d');
        $companies = Company::tenanted()->pluck('name', 'id')->prepend('- Select Company -', '');
        return view('discounts.create', ['companies' => $companies, 'startDate' => $startDate, 'endDate' => $endDate]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDiscountRequest $request)
    {
        list($startDate, $endDate) = explode(' - ', $request->date);

        foreach (arrayFilterAndReindex($request->company_ids) as $company_id) {
            $data = $request->safe()->except(['company_ids', 'date']);
            $data['company_id'] = $company_id;
            $data['start_date'] = $startDate;
            $data['end_date'] = $endDate;
            // dd($data);
            Discount::create($data);
        }
        alert()->success('Success', 'Data created successfully');
        return redirect('discounts');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function show(Discount $discount)
    {
        return view('discounts.show', ['discount' => $discount]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function edit(Discount $discount)
    {
        $companies = Company::tenanted()->pluck('name', 'id')->prepend('- Select Company -', '');
        return view('discounts.edit', ['discounts' => $discount, 'companies' => $companies]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDiscountRequest $request, Discount $discount)
    {
        list($startDate, $endDate) = explode(' - ', $request->date);

        $discount->name = $request->name;
        $discount->description = $request->description;
        $discount->type = $request->type;
        $discount->value = $request->value;
        $discount->is_active = $request->is_active;
        $discount->start_date = $startDate;
        $discount->end_date = $endDate;
        $discount->save();

        alert()->success('Success', 'Data updated successfully');
        return redirect('discounts');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function destroy(Discount $discount)
    {
        try {
            $discount->delete();
        } catch (\Exception $e) {
            return $this->ajaxError($e->getMessage());
        }
        return $this->ajaxSuccess('Data deleted successfully');
    }

    public function massDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:customers,id',
        ]);

        Discount::tenanted()->whereIn('id', $request->ids)->delete();
        alert()->success('Success', 'Data deleted successfully');
        return response(null, 204);
    }
}
