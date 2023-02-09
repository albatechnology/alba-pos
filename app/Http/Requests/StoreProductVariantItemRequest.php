<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantItem;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductVariantItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', function ($attribute, $value, $fail) {
                $productVariantItem = ProductVariantItem::where('product_variant_id', $this->product_variant_id)->where('name', $value)->first();
                if ($productVariantItem) $fail('The product variant item ' . $value . ' already exists ');
            }],
            'price' => 'required|integer|min:0',
        ];
    }
}
