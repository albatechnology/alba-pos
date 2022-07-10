<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductCategoryRequest;
use App\Http\Requests\UpdateProductCategoryRequest;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductCategoryController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:product_categories_access', ['only' => 'index']);
        $this->middleware('permission:product_categories_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:product_categories_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:product_categories_delete', ['only' => ['destroy', 'massDestroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ProductCategory::with('company')->select(sprintf('%s.*', (new ProductCategory)->table));
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('placeholder', '&nbsp;')
                ->editColumn('created_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })
                ->addColumn('company_name', function ($row) {
                    return $row->company?->name ?? '-';
                })
                ->addColumn('actions', function ($row) {
                    $editGate      = 'product_categories_edit';
                    $deleteGate    = 'product_categories_delete';
                    $crudRoutePart = 'product-categories';
                    return view('layouts.includes.datatablesActions', compact('row', 'editGate', 'deleteGate', 'crudRoutePart'));
                })
                ->rawColumns(['placeholder', 'actions'])
                ->make(true);
        }
        return view('productsCategories.index');
    }

    public function create()
    {
        $companies = tenancy()->getCompanies()->pluck('name', 'id')->prepend('- Select Company-', '');

        return view('productsCategories.create', ['companies' => $companies]);
    }

    public function store(StoreProductCategoryRequest $request)
    {
        foreach ($request->company_ids as $company_id) {
            $data = $request->safe()->except(['company_ids']);
            $data['company_id'] = $company_id;
            ProductCategory::create($data);
        }
        alert()->success('Success', 'Data created successfully');
        return redirect('product-categories');
    }

    public function edit(ProductCategory $productCategory)
    {
        $companies = tenancy()->getCompanies()->pluck('name', 'id')->prepend('- Select Company-', '');
        return view('productsCategories.edit', ['productCategory' => $productCategory, 'companies' => $companies]);
    }

    public function update(UpdateProductCategoryRequest $request, ProductCategory $productCategory)
    {
        $productCategory->update($request->validated());

        alert()->success('Success', 'Data updated successfully');
        return redirect('product-categories');
    }

    public function destroy(ProductCategory $productCategory)
    {
        try {
            $productCategory->delete();
        } catch (\Exception $e) {
            return $this->ajaxError($e->getMessage());
        }
        return $this->ajaxSuccess('Data deleted successfully');
    }

    public function massDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:product_categories,id',
        ]);

        ProductCategory::whereIn('id', $request->ids)->delete();
        alert()->success('Success', 'Data deleted successfully');
        return response(null, 204);
    }

    public function ajaxGetProductCategories(Request $request)
    {
        if ($request->ajax()) {
            $productCategories = ProductCategory::query();
            if ($request->company_id) {
                $company_id = explode(',', $request->company_id);
                $productCategories = $productCategories->whereIn('company_id', $company_id ?? []);
            }
            return $productCategories->get(['id', 'name']);
        }
    }
}
