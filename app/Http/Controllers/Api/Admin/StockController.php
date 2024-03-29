<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\StockTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStockRequest;
use App\Http\Requests\UpdateStockRequest;
use App\Http\Resources\StockResource;
use App\Models\Company;
use App\Models\Stock;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class StockController extends Controller
{

    public function index()
    {
        $stock = Stock::simplePaginate();

        return StockResource::collection($stock);
    }

    public function show(Stock $stock, Request $request){

        return view('stocks.show', ['stock' => $stock]);
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

        return response()->json($stock);
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
