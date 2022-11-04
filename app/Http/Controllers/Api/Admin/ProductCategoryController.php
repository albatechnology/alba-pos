<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductCategoryRequest;
use App\Http\Requests\UpdateProductCategoryRequest;
use App\Models\Company;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductCategoryController extends Controller
{

    public function index()
    {
        $productCategory = ProductCategory::simplePaginate();
        return response()->json($productCategory);
    }

    public function store(StoreProductCategoryRequest $request)
    {
        foreach (arrayFilterAndReindex($request->company_ids) as $company_id) {
            $data = $request->safe()->except(['company_ids']);
            $data['company_id'] = $company_id;
            $productCategory = ProductCategory::create($data);
        }
        return response()->json($productCategory, 201);
    }

    public function update(UpdateProductCategoryRequest $request, ProductCategory $productCategory)
    {
        $productCategory->update($request->validated());

        return response()->json($productCategory);
    }

    public function destroy(ProductCategory $productCategory)
    {
        $productCategory->delete();
        return response()->json(['message' => 'Product Category Deleted'], 200);
    }

    public function massDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:product_categories,id',
        ]);

        ProductCategory::tenanted()->whereIn('id', $request->ids)->delete();
        alert()->success('Success', 'Data deleted successfully');
        return response(null, 204);
    }

    public function ajaxGetProductCategories(Request $request)
    {
        if ($request->ajax()) {
            $productCategories = ProductCategory::tenanted();
            if ($request->company_id) {
                $company_id = explode(',', $request->company_id);
                $productCategories = $productCategories->whereIn('company_id', $company_id ?? []);
            }
            return $productCategories->get(['id', 'name']);
        }
    }
}
