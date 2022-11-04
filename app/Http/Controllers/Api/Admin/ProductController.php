<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{

    public function index()
    {
        $product = Product::simplePaginate();

        return ProductResource::collection($product);
    }

    public function store(StoreProductRequest $request)
    {
        foreach (arrayFilterAndReindex($request->company_ids) as $company_id) {
            $data = $request->safe()->except(['company_ids']);
            $data['company_id'] = $company_id;

            $product = Product::create($data);
            $product->productCategories()->sync($request->product_category_ids);
            if ($file = $request->file('image')) {
                $product
                    ->addMedia($file)
                    ->usingName(str_replace(' ', '-', $request->name))
                    ->toMediaCollection('products');
            }
        }
        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        return view('products.show', ['product' => $product]);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        if ($file = $request->file('image')) {
            $product
                ->addMedia($file)
                ->usingName(str_replace(' ', '-', $request->name))
                ->toMediaCollection('products');
        }

        $product->update($request->validated());
        $product->productCategories()->sync($request->product_category_ids);

        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product Deleted'], 200);
    }


    public function massDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:products,id',
        ]);

        Product::tenanted()->whereIn('id', $request->ids)->delete();
        alert()->success('Success', 'Data deleted successfully');
        return response(null, 204);
    }

    public function ajaxGetproducts(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::tenanted();
            if ($request->company_id) {
                $company_id = explode(',', $request->company_id);
                $products = $products->whereIn('company_id', $company_id ?? []);
            }
            return $products->get(['id', 'name']);
        }
    }
}
