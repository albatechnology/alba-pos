<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductBrandRequest;
use App\Http\Requests\UpdateProductBrandRequest;
use App\Http\Resources\ProductBrandResource;
use App\Models\Company;
use App\Models\ProductBrand;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductBrandController extends Controller
{

    public function index()
    {
        $productBrand = ProductBrand::simplePaginate();
        return ProductBrandResource::collection($productBrand);
    }

    public function store(StoreProductBrandRequest $request)
    {
        foreach (arrayFilterAndReindex($request->company_ids) as $company_id) {
            $data = $request->safe()->except(['company_ids']);
            $data['company_id'] = $company_id;
            $productBrand = ProductBrand::create($data);
        }
        return response()->json($productBrand, 201);
    }

    public function update(UpdateProductBrandRequest $request, ProductBrand $productBrand)
    {
        $productBrand->update($request->validated());

        return response()->json($productBrand);
    }

    public function destroy(ProductBrand $productBrand)
    {
        $productBrand->delete();
        return response()->json(['message' => 'Product Brand Deleted'], 200);
    }

    public function massDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:product_brands,id',
        ]);

        ProductBrand::tenanted()->whereIn('id', $request->ids)->delete();
        alert()->success('Success', 'Data deleted successfully');
        return response(null, 204);
    }

    public function ajaxGetProductBrands(Request $request)
    {
        if ($request->ajax()) {
            $productBrands = ProductBrand::tenanted();
            if ($request->company_id) {
                $company_id = explode(',', $request->company_id);
                $productBrands = $productBrands->whereIn('company_id', $company_id ?? []);
            }
            return $productBrands->get(['id', 'name']);
        }
    }
}
