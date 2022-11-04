<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentCategoryResource extends JsonResource
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
            "id" => $this->id ,
            "company" => $this->company->name ,
            "name" => $this->name ,
            "is_exact_change" => $this->is_exact_change,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
