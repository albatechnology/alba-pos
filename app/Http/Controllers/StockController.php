<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStockRequest;
use App\Http\Requests\UpdateStockRequest;
use App\Models\Company;
use App\Models\Stock;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StockController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:stocks_access', ['only' => 'index']);
        $this->middleware('permission:stocks_edit', ['only' => ['edit', 'update']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Stock::tenanted()->with(['company', 'tenant', 'product'])->select(sprintf('%s.*', (new Stock)->table));
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('placeholder', '&nbsp;')
                ->editColumn('created_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })
                ->editColumn('updated_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->updated_at));
                })
                ->addColumn('product_name', function ($row) {
                    return $row->product?->name ?? '-';
                })
                ->addColumn('company_name', function ($row) {
                    return $row->company?->name ?? '-';
                })
                ->addColumn('tenant_name', function ($row) {
                    return $row->tenant?->name ?? '-';
                })
                ->addColumn('actions', function ($row) {
                    $editGate      = 'stock-edit';
                    $deleteGate    = 'stock-delete';
                    $crudRoutePart = 'stocks';
                    return view('layouts.includes.datatablesActions', compact('row', 'editGate', 'deleteGate', 'crudRoutePart'));
                })
                ->rawColumns(['placeholder', 'actions'])
                ->make(true);
        }
        return view('stocks.index');
    }

    public function edit(Stock $stock)
    {
        $companies = Company::tenanted()->pluck('name', 'id')->prepend('- Select Company-', '');
        return view('stocks.edit', ['stock' => $stock, 'companies' => $companies]);
    }

    public function update(UpdateStockRequest $request, Stock $stock)
    {
        $stock->update($request->validated());

        alert()->success('Success', 'Data updated successfully');
        return redirect('stocks');
    }

    public function ajaxGetstocks(Request $request)
    {
        if ($request->ajax()) {
            $stocks = Stock::tenanted();
            if ($request->company_id) {
                $company_id = explode(',', $request->company_id);
                $stocks = $stocks->whereIn('company_id', $company_id ?? []);
            }
            return $stocks->get(['id', 'name']);
        }
    }
}
