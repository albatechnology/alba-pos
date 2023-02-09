<?php

namespace App\Http\Controllers;

use App\Models\ProductVariantItem;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductVariantItemRequest;
use App\Http\Requests\UpdateProductVariantItemRequest;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductVariantItemController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:product_variant_items_access', ['only' => 'index']);
        $this->middleware('permission:product_variant_items_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:product_variant_items_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:product_variant_items_delete', ['only' => ['destroy', 'massDestroy']]);
    }

    public function index(ProductVariant $productVariant, Request $request)
    {
        if ($request->ajax()) {
            $data = ProductVariantItem::with('productVariant')->where('product_variant_id', $productVariant->id)->select(sprintf('%s.*', (new ProductVariantItem)->table));
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('placeholder', '&nbsp;')
                ->editColumn('created_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })
                ->addColumn('product_variant_name', function ($row) {
                    return $row->productVariant?->name ?? '';
                })
                ->addColumn('actions', function ($row) use ($productVariant) {
                    $prefixRoute   = $productVariant->id;
                    $editGate      = 'product_variant_items_edit';
                    $deleteGate    = 'product_variant_items_delete';
                    $crudRoutePart = 'product-variants.product-variant-items';
                    return view('layouts.includes.nestedDatatablesActions', compact('row', 'editGate', 'deleteGate', 'crudRoutePart', 'prefixRoute'));
                })
                ->rawColumns(['placeholder', 'actions'])
                ->make(true);
        }
        return view('productVariants.relationships.productVariantItems.index', ['productVariant' => $productVariant]);
    }

    public function create(ProductVariant $productVariant)
    {
        return view('productVariants.relationships.productVariantItems.create', ['productVariant' => $productVariant]);
    }

    public function store(ProductVariant $productVariant, StoreProductVariantItemRequest $request)
    {
        $productVariant->productVariantItems()->create($request->validated());

        alert()->success('Success', 'Data created successfully');
        return redirect()->route('product-variants.product-variant-items.index', $productVariant->id);
    }


    public function edit(ProductVariant $productVariant, ProductVariantItem $productVariantItem)
    {
        return view('productVariants.relationships.productVariantItems.edit', ['productVariant' => $productVariant,'productVariantItem' => $productVariantItem]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductVariantItem  $productVariantItem
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductVariantItemRequest $request, ProductVariant $productVariant, ProductVariantItem $productVariantItem)
    {
        // dd($request);
        $productVariant->productVariantItems()->where('id', $productVariantItem->id)->update($request->validated());

        alert()->success('Success', 'Data updated successfully');
        return redirect()->route('product-variants.product-variant-items.index', $productVariant->id);
    }

    public function destroy(ProductVariant $productVariant, ProductVariantItem $productVariantItem)
    {
        try {
            $productVariantItem->delete();
        } catch (\Exception $e) {
            return $this->ajaxError($e->getMessage());
        }
        return $this->ajaxSuccess('Data deleted successfully');
    }
}
