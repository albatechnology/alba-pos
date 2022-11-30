<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\BankAccount;
use App\Models\Company;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:suppliers_access', ['only' => 'index']);
        $this->middleware('permission:suppliers_view', ['only' => 'show']);
        $this->middleware('permission:suppliers_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:suppliers_edit', ['only' => ['edit', 'update']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Supplier::tenanted()->with(['company'])->select(sprintf('%s.*', (new Supplier)->table));
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('placeholder', '&nbsp;')
                ->addColumn('company_name', function ($row) {
                    return $row->company?->name ?? '';
                })
                ->editColumn('created_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })
                ->editColumn('updated_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->updated_at));
                })
                ->addColumn('actions', function ($row) {
                    $extraActions  = '';
                    if (user()->can('bank_accounts_access')) {
                        $extraActions .= '<a class="btn btn-warning btn-sm" href="' . route('suppliers.bank-accounts.index', $row->id) . '">Bank Account</a>';
                    }
                    $viewGate      = 'suppliers_view';
                    $editGate      = 'suppliers_edit';
                    $deleteGate    = 'suppliers_delete';
                    $crudRoutePart = 'suppliers';
                    return view('layouts.includes.datatablesActions', compact('row', 'viewGate', 'editGate', 'deleteGate', 'crudRoutePart', 'extraActions'));
                })
                ->rawColumns(['placeholder', 'actions'])
                ->make(true);
        }
        return view('suppliers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::tenanted()->pluck('name', 'id')->prepend('- Select Company -', '');

        return view('suppliers.create', ['companies' => $companies]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSupplierRequest $request)
    {
        $supplier = Supplier::create([
            'company_id' => $request->company_id,
            'code' => $request->code,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'province_id' => $request->province_id,
            'city_id' => $request->city_id,
            'district_id' => $request->district_id,
            'village_id' => $request->village_id,
            'description' => $request->description,
        ]);

        BankAccount::create([
            'bank_accountable_id' => $supplier->id,
            'bank_accountable_type' => 'App\Models\Supplier',
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'bank_name' => $request->bank_name
        ]);
        alert()->success('Success', 'Data created successfully');
        return redirect('suppliers');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        return view('suppliers.show', ['supplier' => $supplier]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', ['supplier' => $supplier]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $supplier->update($request->validated());

        alert()->success('Success', 'Data updated successfully');
        return redirect('suppliers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        try {
            $supplier->delete();
        } catch (\Exception $e) {
            return $this->ajaxError($e->getMessage());
        }
        return $this->ajaxSuccess('Data deleted successfully');
    }
}
