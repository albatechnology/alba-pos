<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
            "id" => $this->id ,
            "company" => $this->company->name ,
            "product_brand" => $this->productBrand->name,
            "code" => $this->code,
            "name" => $this->name,
            "uom" => $this->uom ,
            "price" => $this->price ,
            "tax" => $this->tax ,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
