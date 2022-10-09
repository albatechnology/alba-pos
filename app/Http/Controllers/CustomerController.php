<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Company;
use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:customers_access', ['only' => 'index']);
        $this->middleware('permission:customers_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:customers_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:customers_delete', ['only' => ['destroy', 'massDestroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Customer::tenanted()->with('company')->select(sprintf('%s.*', (new Customer)->table));
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
                ->addColumn('tenant_name', function ($row) {
                    return $row->tenant?->name ?? '';
                })
                ->addColumn('actions', function ($row) {
                    $viewGate      = 'customers_show';
                    $editGate      = 'customers_edit';
                    $deleteGate    = 'customers_delete';
                    $crudRoutePart = 'customers';
                    return view('layouts.includes.datatablesActions', compact('row', 'viewGate', 'editGate', 'deleteGate', 'crudRoutePart'));
                })
                ->rawColumns(['placeholder', 'actions'])
                ->make(true);
        }
        return view('customers.index');
    }

    public function create()
    {
        $companies = Company::tenanted()->pluck('name', 'id')->prepend('- Select Company -', '');

        return view('customers.create', ['companies' => $companies]);
    }

    public function store(StoreCustomerRequest $request)
    {
        Customer::create($request->validated());
        alert()->success('Success', 'Data created successfully');
        return redirect('customers');
    }

    public function edit(Customer $customer)
    {
        $companies = Company::tenanted()->pluck('name', 'id')->prepend('- Select Company-', '');
        return view('customers.edit', ['customer' => $customer, 'companies' => $companies]);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->validated());

        alert()->success('Success', 'Data updated successfully');
        return redirect('customers');
    }

    public function show(Customer $customer)
    {
        return view('customers.show', ['customer' => $customer]);
    }

    public function destroy(Customer $customer)
    {
        try {
            $customer->delete();
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

        Customer::tenanted()->whereIn('id', $request->ids)->delete();
        alert()->success('Success', 'Data deleted successfully');
        return response(null, 204);
    }

    public function ajaxGetcustomers(Request $request)
    {
        if ($request->ajax()) {
            $customers = Customer::tenanted();
            if ($request->company_id) {
                $company_id = explode(',', $request->company_id);
                $customers = $customers->whereIn('company_id', $company_id ?? []);
            }
            return $customers->get(['id', 'name']);
        }
    }
}
