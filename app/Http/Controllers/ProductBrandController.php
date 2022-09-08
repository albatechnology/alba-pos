<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductBrandRequest;
use App\Http\Requests\UpdateProductBrandRequest;
use App\Models\Company;
use App\Models\ProductBrand;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductBrandController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:product_brands_access', ['only' => 'index']);
        $this->middleware('permission:product_brands_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:product_brands_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:product_brands_delete', ['only' => ['destroy', 'massDestroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ProductBrand::tenanted()->with('company')->select(sprintf('%s.*', (new ProductBrand)->table));
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
                    $editGate      = 'product_brands_edit';
                    $deleteGate    = 'product_brands_delete';
                    $crudRoutePart = 'product-brands';
                    return view('layouts.includes.datatablesActions', compact('row', 'editGate', 'deleteGate', 'crudRoutePart'));
                })
                ->rawColumns(['placeholder', 'actions'])
                ->make(true);
        }
        return view('productsBrands.index');
    }

    public function create()
    {
        $companies = Company::tenanted()->pluck('name', 'id')->prepend('- Select Company-', '');

        return view('productsBrands.create', ['companies' => $companies]);
    }

    public function store(StoreProductBrandRequest $request)
    {
        foreach (arrayFilterAndReindex($request->company_ids) as $company_id) {
            $data = $request->safe()->except(['company_ids']);
            $data['company_id'] = $company_id;
            ProductBrand::create($data);
        }
        alert()->success('Success', 'Data created successfully');
        return redirect('product-brands');
    }

    public function edit(ProductBrand $productBrand)
    {
        $companies = Company::tenanted()->pluck('name', 'id')->prepend('- Select Company-', '');
        return view('productsBrands.edit', ['productBrand' => $productBrand, 'companies' => $companies]);
    }

    public function update(UpdateProductBrandRequest $request, ProductBrand $productBrand)
    {
        $productBrand->update($request->validated());

        alert()->success('Success', 'Data updated successfully');
        return redirect('product-brands');
    }

    public function destroy(ProductBrand $productBrand)
    {
        try {
            $productBrand->delete();
        } catch (\Exception $e) {
            return $this->ajaxError($e->getMessage());
        }
        return $this->ajaxSuccess('Data deleted successfully');
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
