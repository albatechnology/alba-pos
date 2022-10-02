<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProductTenantRequest;
use App\Models\Product;
use App\Models\ProductTenant;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductTenantControllerCopy extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ProductTenant::tenanted()->with(['company', 'tenant', 'product'])->select(sprintf('%s.*', (new ProductTenant())->table));
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('placeholder', '&nbsp;')
                ->editColumn('created_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })
                ->editColumn('uom', function ($row) {
                    return $row->uom?$row->uom:$row->product->uom;
                })
                ->editColumn('price', function ($row) {
                    return $row->price?$row->price:$row->product->price;
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
                ->addColumn('actions', function ($row) {
                    $editGate      = 'product_tenants_edit';
                    $crudRoutePart = 'product-tenants';
                    return view('layouts.includes.datatablesActions', compact('row', 'editGate', 'crudRoutePart'));
                })
                ->rawColumns(['placeholder', 'actions'])
                ->make(true);
        }
        return view('productTenants.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductTenant $productTenant)
    {
        $products = Product::tenanted()->pluck('price', 'uom', 'name', 'id');

        return view('productTenants.edit', ['productTenant' => $productTenant, 'products' => $products]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductTenantRequest $request, ProductTenant $productTenant)
    {
        $productTenant->update($request->validated());


        alert()->success('Success', 'Data updated successfully');
        return redirect('product-tenants');
    }
}
