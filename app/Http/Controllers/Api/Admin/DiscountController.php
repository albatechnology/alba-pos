<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDiscountRequest;
use App\Http\Requests\UpdateDiscountRequest;
use App\Http\Resources\DiscountResource;
use App\Models\Company;
use App\Models\Discount;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DiscountController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $discount = Discount::simplePaginate();

        return DiscountResource::collection($discount);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDiscountRequest $request)
    {
        list($startDate, $endDate) = explode(' - ', $request->date);

        foreach (arrayFilterAndReindex($request->company_ids) as $company_id) {
            $data = $request->safe()->except(['company_ids', 'date']);
            $data['company_id'] = $company_id;
            $data['start_date'] = $startDate;
            $data['end_date'] = $endDate;
            // dd($data);
            Discount::create($data);
        }
        return response()->json($data, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function show(Discount $discount)
    {
        return view('discounts.show', ['discount' => $discount]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDiscountRequest $request, Discount $discount)
    {
        list($startDate, $endDate) = explode(' - ', $request->date);

        $discount->name = $request->name;
        $discount->description = $request->description;
        $discount->type = $request->type;
        $discount->value = $request->value;
        $discount->is_active = $request->is_active;
        $discount->start_date = $startDate;
        $discount->end_date = $endDate;
        $discount->save();

        return response()->json($discount);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function destroy(Discount $discount)
    {
        $discount->delete();
        return response()->json(['message' => 'Discount Deleted'], 200);
    }

    public function massDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:customers,id',
        ]);

        Discount::tenanted()->whereIn('id', $request->ids)->delete();
        alert()->success('Success', 'Data deleted successfully');
        return response(null, 204);
    }
}
