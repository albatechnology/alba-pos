<?php

namespace App\Http\Requests;

use App\Models\Company;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
                foreach ($this->company_ids as $company_id) {
                    $product = Product::where('company_id', $company_id)->where('name', $value)->first();
                    if ($product) $fail('The product ' . $value . ' is already in company ' . $product->company->name);
                }
            }],
            'price' => 'required|integer|min:0',
            'uom' => 'required|integer|min:1',
            'company_ids' => ['required','array', function($attribute, $value, $fail){
                $companies = Company::tenantedMyCompanies()->whereIn('id', $value)->count();
                if($companies < count($value)) $fail('Invalid company');
            }],
        ];
    }
}
