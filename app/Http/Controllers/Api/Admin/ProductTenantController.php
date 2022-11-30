<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProductTenantRequest;
use App\Http\Resources\ProductTenantResource;
use App\Models\Product;
use App\Models\ProductTenant;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductTenantController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productTenant = ProductTenant::simplePaginate();

        return ProductTenantResource::collection($productTenant);
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
