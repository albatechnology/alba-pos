<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductVariantRequest;
use App\Http\Requests\UpdateProductVariantRequest;
use App\Models\Company;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductVariantController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:product_variants_access', ['only' => 'index']);
        $this->middleware('permission:product_variants_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:product_variants_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:product_variants_delete', ['only' => ['destroy', 'massDestroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ProductVariant::tenanted()->with('company')->select(sprintf('%s.*', (new ProductVariant)->table));
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('placeholder', '&nbsp;')
                ->editColumn('created_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })
                ->addColumn('company_name', function ($row) {
                    return $row->company?->name ?? '';
                })
                ->addColumn('actions', function ($row) {
                    $extraActions  = '';
                    if (user()->can('product_variant_items_access')) {
                        $extraActions .= '<a class="btn btn-warning btn-sm" href="' . route('product-variants.product-variant-items.index', $row->id) . '">Variant Items</a>';
                    }
                    $editGate      = 'product_variants_edit';
                    $deleteGate    = 'product_variants_delete';
                    $crudRoutePart = 'product-variants';
                    return view('layouts.includes.datatablesActions', compact('row', 'editGate', 'deleteGate', 'crudRoutePart', 'extraActions'));
                })
                ->rawColumns(['placeholder', 'actions'])
                ->make(true);
        }
        return view('productVariants.index');
    }

    public function create()
    {
        $companies = Company::tenanted()->pluck('name', 'id')->prepend('- Select Company -', '');
        return view('productVariants.create', ['companies' => $companies]);
    }

    public function store(StoreProductVariantRequest $request)
    {
        foreach (arrayFilterAndReindex($request->company_ids) as $company_id) {
            $data = $request->safe()->except(['company_ids']);
            $data['company_id'] = $company_id;
            ProductVariant::create($data);
        }
        alert()->success('Success', 'Data created successfully');
        return redirect('product-variants');
    }

    public function edit(ProductVariant $productVariant)
    {
        $companies = Company::tenanted()->pluck('name', 'id')->prepend('- Select Company -', '');
        return view('productVariants.edit', ['productVariant' => $productVariant, 'companies' => $companies]);
    }

    public function update(UpdateProductVariantRequest $request, ProductVariant $productVariant)
    {
        $productVariant->update($request->validated());

        alert()->success('Success', 'Data updated successfully');
        return redirect('product-variants');
    }

    public function destroy(ProductVariant $productVariant)
    {
        try {
            $productVariant->delete();
        } catch (\Exception $e) {
            return $this->ajaxError($e->getMessage());
        }
        return $this->ajaxSuccess('Data deleted successfully');
    }
}
