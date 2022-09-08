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
                    $editGate      = 'stock-edit';
                    $crudRoutePart = 'stocks';
                    return view('layouts.includes.datatablesActions', compact('row', 'editGate', 'crudRoutePart'));
                })
                ->rawColumns(['placeholder', 'actions'])
                ->make(true);
        }
        return view('stocks.index');
    }

    public function edit(Stock $stock)
    {
        return view('stocks.edit', ['stock' => $stock]);
    }

    public function update(UpdateStockRequest $request, Stock $stock)
    {

        if ($request->option == '1') {
            $stock->stock = $stock->stock + $request->amount;
        } else {
            $stock->stock = $stock->stock - $request->amount;
        }
        $stock->update();

        // $stockIds = Stock::where('stock_id', $stock->stockid);
        // if ($stockIds->count() > 0) {
        //     $stockIds->map(function ($stockId) use ($request) {
                if ($request->option == '1') {
                    StockHistory::Create(
                        [
                            'stock_id' => $stock->id,
                            'user_id' => Auth::user()->id,
                            'type' => StockTypeEnum::INCREASE,
                            'amount' => $request->amount,
                            'source' => 'Stock'
                        ]
                    );
                } else {
                    StockHistory::Create(
                        [
                            'stock_id' => $stock->id,
                            'user_id' => Auth::user()->id,
                            'type' => StockTypeEnum::DECREASE,
                            'amount' => $request->amount,
                            'source' => 'Stock'
                        ]
                    );
                }

                // StockHistory::Create(
                //     [
                //         'stock_id' => $stock->id,
                //         'user_id' => Auth::user()->id,
                //         'type' => $request->option == '1' ? StockTypeEnum::INCREASE : StockTypeEnum::DECREASE,
                //         'amount' => $request->amount,
                //         'source' => 'Stock'
                //     ]
                // );

        //     });
        // }
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
