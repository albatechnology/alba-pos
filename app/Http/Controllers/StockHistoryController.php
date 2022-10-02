<?php

namespace App\Http\Controllers;

use App\Models\StockHistory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StockHistoryController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:stock_histories_access', ['only' => 'index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = StockHistory::with(['stock']);
            if ($stock_id = $request->stock_id) $data = $data->where('stock_id', $stock_id);
            $data = $data->select(sprintf('%s.*', (new StockHistory())->table));

            return DataTables::of($data)->addIndexColumn()
                ->addColumn('placeholder', '&nbsp;')
                ->editColumn('created_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })
                ->editColumn('updated_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->updated_at));
                })
                ->editColumn('type', function ($row) {
                    return $row->type->description;
                })
                ->addColumn('stock_id', function ($row) {
                    return $row->stock?->id ?? '';
                })
                ->addColumn('actions', function ($row) {
                    // $editGate      = 'stock-histories-edit';
                    $crudRoutePart = 'stock-histories';
                    return view('layouts.includes.datatablesActions', compact('row', 'crudRoutePart'));
                })
                ->rawColumns(['placeholder', 'actions'])
                ->make(true);
        }
        return view('stocksHistories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StockHistory  $stockHistory
     * @return \Illuminate\Http\Response
     */
    public function show(StockHistory $stockHistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StockHistory  $stockHistory
     * @return \Illuminate\Http\Response
     */
    public function edit(StockHistory $stockHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StockHistory  $stockHistory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockHistory $stockHistory)
    {
        //
    }
}
