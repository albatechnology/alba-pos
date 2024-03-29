<?php

namespace App\Http\Controllers;

use App\Enums\StockTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStockRequest;
use App\Http\Requests\UpdateStockRequest;
use App\Models\Company;
use App\Models\Stock;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class StockController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:stocks_access', ['only' => 'index']);
        $this->middleware('permission:stocks_view', ['only' => 'show']);
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
                    return $row->product?->name ?? '';
                })
                ->addColumn('company_name', function ($row) {
                    return $row->company?->name ?? '';
                })
                ->addColumn('tenant_name', function ($row) {
                    return $row->tenant?->name ?? '';
                })
                ->addColumn('actions', function ($row) {
                    $viewGate      = 'stocks_view';
                    $editGate      = 'stocks_edit';
                    $crudRoutePart = 'stocks';
                    return view('layouts.includes.datatablesActions', compact('row', 'viewGate', 'editGate', 'crudRoutePart'));
                })
                ->rawColumns(['placeholder', 'actions'])
                ->make(true);
        }
        return view('stocks.index');
    }

    public function show(Stock $stock, Request $request){

        return view('stocks.show', ['stock' => $stock]);
    }

    public function edit(Stock $stock)
    {
        return view('stocks.edit', ['stock' => $stock]);
    }

    public function update(UpdateStockRequest $request, Stock $stock)
    {
        $oldAmount = $stock->stock;
        if ($request->option == 'increase') {
            $stock->stock = $stock->stock + $request->amount;
        } else {
            $stock->stock = $stock->stock - $request->amount;
        }

        if ($stock->stock < 0) {
            alert()->error('Error', 'Data may not below 0');
            return redirect('stocks');
        }
        $stock->update();

        StockHistory::create(
            [
                'stock_id' => $stock->id,
                'user_id' => Auth::user()->id,
                'type' => $request->option == 'increase' ? StockTypeEnum::INCREASE : StockTypeEnum::DECREASE,
                'changes' => $request->amount,
                'old_amount' => $oldAmount,
                'new_amount' => $stock->stock,
                'source' => 'Stock'
            ]
        );

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
