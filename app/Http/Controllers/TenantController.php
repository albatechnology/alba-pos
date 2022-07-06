<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class TenantController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:tenants_access', ['only' => 'index']);
        $this->middleware('permission:tenants_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:tenants_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:tenants_delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Tenant::with('company')->select(sprintf('%s.*', (new Tenant)->table))->orderByDesc('id');
            return DataTables::of($data)->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })
                ->addColumn('company_name', function ($row) {
                    return $row->company?->name ?? '-';
                })
                ->addColumn('actions', function ($row) {
                    $editGate      = 'tenant-edit';
                    $deleteGate    = 'tenant-delete';
                    $crudRoutePart = 'tenants';
                    return view('layouts.includes.datatablesActions', compact('row', 'editGate', 'deleteGate', 'crudRoutePart'));
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('tenants.index');
    }

    public function create()
    {
        $companies = tenancy()->getCompanies();
        return view('tenants.create', ['companies' => $companies]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:tenants,name',
            'company_id' => 'required|exists:companies,id',
        ]);

        tenant::create($validated);

        return redirect('tenants')->withStatus($this->flash_data('success', __('global.created_successfully')));
    }

    public function edit(Tenant $tenant)
    {
        $companies = tenancy()->getCompanies();
        return view('tenants.edit', ['tenant' => $tenant, 'companies' => $companies]);
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|unique:tenants,name,' . $tenant->id,
            'company_id' => 'required|exists:companies,id',
        ]);
        $tenant->update($validated);

        return redirect('tenants')->withStatus($this->flash_data('success', __('global.updated_successfully')));
    }

    public function destroy($id)
    {
        try {
            Tenant::destroy($id);
        } catch (\Exception $e) {
            return $this->ajaxError($e->getMessage());
        }
        return $this->ajaxSuccess(__('global.deleted_successfully'));
    }

    /**
     * set active tenant for this session
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function setActiveTenant(Request $request): RedirectResponse
    {
        tenancy()->setActiveTenantFromRequest($request);

        return redirect()->back();
    }

    public function ajaxGetTenants(Request $request)
    {
        if ($request->ajax()) {
            $tenants = Tenant::query();
            if ($request->company_id) {
                $company_id = explode(',', $request->company_id);
                $tenants = $tenants->whereIn('company_id', $company_id ?? []);
            }
            return $tenants->get(['id', 'name']);
        }
    }
}
