<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProductTenantRequest;
use App\Models\Product;
use App\Models\ProductTenant;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductTenantController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:product_tenants_access', ['only' => 'index']);
        // $this->middleware('permission:product_tenants_show', ['only' => 'show']);
        // $this->middleware('permission:product_tenants_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:product_tenants_edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:product_tenants_delete', ['only' => ['destroy', 'massDestroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product, Request $request)
    {
        if ($request->ajax()) {
            $data = ProductTenant::tenanted()->whereProductId($product->id)->with(['tenant', 'product'])->select(sprintf('%s.*', (new ProductTenant())->table));

            return DataTables::of($data)->addIndexColumn()
                ->addColumn('placeholder', '&nbsp;')
                ->editColumn('created_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })
                ->editColumn('uom', function ($row) {
                    return $row->uom ? $row->uom : $row->product->uom;
                })
                ->editColumn('price', function ($row) {
                    return $row->price ? $row->price : $row->product->price;
                })
                ->addColumn('company_name', function ($row) {
                    return $row->tenant?->company?->name ?? '';
                })
                ->addColumn('tenant_name', function ($row) {
                    return $row->tenant?->name ?? '';
                })
                ->addColumn('product_name', function ($row) {
                    return $row->product?->name ?? '';
                })
                ->addColumn('actions', function ($row) use ($product) {
                    $prefixRoute      = $product->id;
                    $editGate      = 'product_tenants_edit';
                    // $deleteGate      = 'product_tenants_delete';
                    $crudRoutePart = 'products.tenants';
                    return view('layouts.includes.nestedDatatablesActions', compact('row', 'editGate', 'crudRoutePart', 'prefixRoute'));
                })
                ->rawColumns(['placeholder', 'actions'])
                ->make(true);
        }
        return view('productTenants.index', ['product' => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product, ProductTenant $tenant)
    {
        return view('productTenants.edit', ['product' => $product, 'productTenant' => $tenant]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductTenantRequest $request, Product $product, ProductTenant $tenant)
    {
        $tenant->update($request->validated());

        alert()->success('Success', 'Data updated successfully');
        return redirect()->route('products.tenants.index', $product->id);
    }
}
