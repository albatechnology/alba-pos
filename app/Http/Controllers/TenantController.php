<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductTenant;
use App\Models\Stock;
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
        $this->middleware('permission:tenants_delete', ['only' => ['destroy', 'massDestroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Tenant::getAllMyTenant()->with('company')->select(sprintf('%s.*', (new Tenant)->table));
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
                    $editGate      = 'tenant-edit';
                    $deleteGate    = 'tenant-delete';
                    $crudRoutePart = 'tenants';
                    return view('layouts.includes.datatablesActions', compact('row', 'editGate', 'deleteGate', 'crudRoutePart'));
                })
                ->rawColumns(['placeholder', 'actions'])
                ->make(true);
        }
        return view('tenants.index');
    }

    public function create()
    {
        // $companies = Company::tenanted()->get();
        $companies = Company::tenanted()->pluck('name', 'id')->prepend('- Select Company -', '');
        return view('tenants.create', ['companies' => $companies]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'company_id' => 'required|exists:companies,id',
        ]);

        $tenant = Tenant::create($validated);

        $products = Product::where('company_id', $tenant->company_id)->get();
        foreach ($products as $product) {
            ProductTenant::create([
                'tenant_id' => $tenant->id,
                'product_id' => $product->id,
                'uom' => $product->uom,
                'price' => $product->price,
            ]);

            Stock::firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'product_id' => $product->id
                ],
                [
                    'company_id' => $product->company_id,
                    'stock' => 0
                ]
            );
        }

        alert()->success('Success', 'Data created successfully');
        return redirect('tenants')->withStatus($this->flash_data('success', __('global.created_successfully')));
    }

    public function edit(Tenant $tenant)
    {
        // $companies = Company::tenanted()->get();
        $companies = Company::tenanted()->pluck('name', 'id')->prepend('- Select Company -', '');
        return view('tenants.edit', ['tenant' => $tenant, 'companies' => $companies]);
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => 'required',
            'company_id' => 'required|exists:companies,id',
        ]);
        $tenant->update($validated);
        alert()->success('Success', 'Data updated successfully');
        return redirect('tenants')->withStatus($this->flash_data('success', __('global.updated_successfully')));
    }

    public function destroy($id)
    {
        try {
            Tenant::destroy($id);
        } catch (\Exception $e) {
            return $this->ajaxError($e->getMessage());
        }
        return $this->ajaxSuccess('Data deleted successfully');
    }

    public function massDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:companies,id',
        ]);

        Tenant::whereIn('id', $request->ids)->delete();
        alert()->success('Success', 'Data deleted successfully');
        return response(null, 204);
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
            $tenants = Tenant::tenanted();
            if ($request->company_id) {
                $company_id = explode(',', $request->company_id);
                $tenants = $tenants->whereIn('company_id', $company_id ?? []);
            }
            return $tenants->get(['id', 'name']);
        }
    }
}
