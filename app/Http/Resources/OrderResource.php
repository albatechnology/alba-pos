<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'status' => $this->status->description
        ];
        return array_merge(parent::toArray($request), [
            'tenant' => new TenantResource($this->tenant),
            'order_details' => OrderDetailResource::collection($this->orderDetails),
        ]);
        // return [
        //     'id' => $this->id,
        //     'invoice_number' => $this->invoice_number,
        //     'total_price' => $this->total_price,
        // ];
    }
}
