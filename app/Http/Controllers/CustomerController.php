<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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
        $this->middleware('permission:customers_delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Customer::with('company')->select(sprintf('%s.*', (new Customer)->table))->orderByDesc('id');
            return DataTables::of($data)->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })
                ->addColumn('company_name', function ($row) {
                    return $row->company?->name ?? '-';
                })
                ->addColumn('actions', function ($row) {
                    $editGate      = 'customer-edit';
                    $deleteGate    = 'customer-delete';
                    $crudRoutePart = 'customers';
                    return view('layouts.includes.datatablesActions', compact('row', 'editGate', 'deleteGate', 'crudRoutePart'));
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('customers.index');
    }

    public function create()
    {
        $companies = tenancy()->getCompanies();
        return view('customers.create', ['companies' => $companies]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:customers,name',
            'company_id' => 'required|exists:companies,id',
        ]);

        Customer::create($validated);

        return redirect('customers')->withStatus($this->flash_data('success', __('global.created_successfully')));
    }

    public function edit(Customer $customer)
    {
        $companies = tenancy()->getCompanies();
        return view('customers.edit', ['customer' => $customer, 'companies' => $companies]);
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|unique:customers,name,' . $customer->id,
            'company_id' => 'required|exists:companies,id',
        ]);
        $customer->update($validated);

        return redirect('customers')->withStatus($this->flash_data('success', __('global.updated_successfully')));
    }

    public function destroy(Customer $customer)
    {
        try {
            $customer->delete();
        } catch (\Exception $e) {
            return $this->ajaxError($e->getMessage());
        }
        return $this->ajaxSuccess(__('global.deleted_successfully'));
    }

    public function ajaxGetcustomers(Request $request)
    {
        if ($request->ajax()) {
            $customers = Customer::query();
            if ($request->company_id) {
                $company_id = explode(',', $request->company_id);
                $customers = $customers->whereIn('company_id', $company_id ?? []);
            }
            return $customers->get(['id', 'name']);
        }
    }
}
