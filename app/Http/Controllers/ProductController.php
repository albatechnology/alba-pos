<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:products_access', ['only' => 'index']);
        $this->middleware('permission:products_show', ['only' => 'show']);
        $this->middleware('permission:products_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:products_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:products_delete', ['only' => ['destroy', 'massDestroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::tenanted()->with(['company', 'productCategories'])->select(sprintf('%s.*', (new Product)->table));
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('placeholder', '&nbsp;')
                ->editColumn('created_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })
                ->addColumn('company_name', function ($row) {
                    return $row->company?->name ?? '';
                })
                ->addColumn('product_categories', function ($row) {
                    $html = '';
                    $productCategories = $row->productCategories;
                    if ($productCategories->count() > 0) {
                        foreach ($productCategories as $category) {
                            $html .= '<div class="badge badge-info">' . $category->name . '</div><br>';
                        }
                    }
                    return $html;
                })
                ->addColumn('actions', function ($row) {
                    $viewGate      = 'products_show';
                    $editGate      = 'products_edit';
                    $deleteGate    = 'products_delete';
                    $crudRoutePart = 'products';
                    return view('layouts.includes.datatablesActions', compact('row', 'viewGate', 'editGate', 'deleteGate', 'crudRoutePart'));
                })
                ->rawColumns(['placeholder', 'actions', 'product_categories'])
                ->make(true);
        }
        return view('products.index');
    }

    public function create()
    {
        $companies = Company::tenanted()->pluck('name', 'id')->prepend('- Select Company -', '');
        $productCategories = ProductCategory::tenanted()->pluck('name', 'id')->prepend('- Select Product Categories -', '');
        $productBrands = ProductBrand::tenanted()->pluck('name', 'id')->prepend('- Select Product Brand -', '');

        return view('products.create', ['companies' => $companies, 'productCategories' => $productCategories, 'productBrands' => $productBrands]);
    }

    public function store(StoreProductRequest $request)
    {
        foreach (arrayFilterAndReindex($request->company_ids) as $company_id) {
            $data = $request->safe()->except(['company_ids']);
            $data['company_id'] = $company_id;

            $product = Product::create($data);
            $product->productCategories()->sync($request->product_category_ids);
        }
        alert()->success('Success', 'Data created successfully');
        return redirect('products');
    }

    public function edit(Product $product)
    {
        $companies = Company::tenanted()->pluck('name', 'id')->prepend('- Select Company -', '');
        $productCategories = ProductCategory::tenanted()->pluck('name', 'id')->prepend('- Select Product Categories -', '');
        $productBrands = ProductBrand::tenanted()->pluck('name', 'id')->prepend('- Select Product Brand -', '');

        $selectedProductCategories = $product->productCategories->pluck('id')->all() ?? [];

        return view('products.edit', ['product' => $product, 'selectedProductCategories' => $selectedProductCategories, 'companies' => $companies, 'productCategories' => $productCategories, 'productBrands' => $productBrands]);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        $product->productCategories()->sync($request->product_category_ids);

        alert()->success('Success', 'Data updated successfully');
        return redirect('products');
    }

    public function destroy(Product $product)
    {
        try {
            $product->delete();
        } catch (\Exception $e) {
            return $this->ajaxError($e->getMessage());
        }
        return $this->ajaxSuccess('Data deleted successfully');
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
