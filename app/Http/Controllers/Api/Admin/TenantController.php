<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\TenantResource;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductTenant;
use App\Models\Stock;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class TenantController extends Controller
{

    public function index()
    {
        $tenant = Tenant::simplePaginate();

        return TenantResource::collection($tenant);
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
        return response()->json($tenant, 201);
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => 'required',
            'company_id' => 'required|exists:companies,id',
        ]);
        $tenant->update($validated);
        return response()->json($tenant);
    }

    public function destroy(Tenant $tenant)
    {
        $tenant->delete();
        return response()->json(['message' => 'Tenant Deleted'], 200);
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
