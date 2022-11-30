<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerGroupRequest;
use App\Http\Requests\UpdateCustomerGroupRequest;
use App\Models\Company;
use App\Models\CustomerGroup;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerGroupController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:customer_groups_access', ['only' => 'index']);
        $this->middleware('permission:customer_groups_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:customer_groups_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:customer_groups_delete', ['only' => ['destroy', 'massDestroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = CustomerGroup::tenanted()->with('company', 'customers')->select(sprintf('%s.*', (new CustomerGroup)->table));
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
                ->addColumn('group_members', function ($row) {
                    return $row->customers?->count() ?? '';
                })
                ->addColumn('actions', function ($row) {
                    $viewGate      = 'customer_groups_show';
                    $editGate      = 'customer_groups_edit';
                    $deleteGate    = 'customer_groups_delete';
                    $crudRoutePart = 'customer-groups';
                    return view('layouts.includes.datatablesActions', compact('row', 'viewGate', 'editGate', 'deleteGate', 'crudRoutePart'));
                })
                ->rawColumns(['placeholder', 'actions'])
                ->make(true);
        }
        return view('customerGroups.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::tenanted()->pluck('name', 'id')->prepend('- Select Company -', '');

        return view('customerGroups.create', ['companies' => $companies]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustomerGroupRequest $request)
    {
        CustomerGroup::create($request->validated());

        alert()->success('Success', 'Data created successfully');
        return redirect('customer-groups');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomerGroup  $customerGroup
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerGroup $customerGroup)
    {
        return view('customerGroups.show', ['customerGroup' => $customerGroup]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomerGroup  $customerGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerGroup $customerGroup)
    {
        $companies = Company::tenanted()->pluck('name', 'id')->prepend('- Select Company-', '');
        return view('customerGroups.edit', ['customerGroup' => $customerGroup, 'companies' => $companies]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomerGroupGroup  $customerGroup
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomerGroupRequest $request, CustomerGroup $customerGroup)
    {
        $customerGroup->update($request->validated());

        alert()->success('Success', 'Data updated successfully');
        return redirect('customer-groups');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerGroup  $customerGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerGroup $customerGroup)
    {
        try {
            $customerGroup->delete();
        } catch (\Exception $e) {
            return $this->ajaxError($e->getMessage());
        }
        return $this->ajaxSuccess('Data deleted successfully');
    }

    public function massDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:customer_groups,id',
        ]);

        CustomerGroup::tenanted()->whereIn('id', $request->ids)->delete();
        alert()->success('Success', 'Data deleted successfully');
        return response(null, 204);
    }

    public function ajaxGetCustomerGroups(Request $request)
    {
        if ($request->ajax()) {
            $customerGroups = CustomerGroup::tenanted();
            if ($request->tenant_id) {
                $tenant_id = explode(',', $request->tenant_id);
                $customerGroups = $customerGroups->whereIn('tenant_id', $tenant_id ?? []);

                // $customerGroups = $customerGroups->whereHas('tenant', function ($q) use ($company_id) {
                //     $q->whereIn('company_id', $company_id ?? []);
                // });

                // $customerGroups = $customerGroups->whereHas('tenant', fn ($q) => $q->whereIn('company_id', $company_id ?? []));
            }
            return $customerGroups->get(['id', 'name']);
        }
    }
}
