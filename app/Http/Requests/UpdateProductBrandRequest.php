<?php

namespace App\Http\Requests;

use App\Models\Company;
use App\Models\ProductBrand;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductBrandRequest extends FormRequest
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
        $oldProductBrand = $this->route('product_brand');
        return [
            'company_id' => ['required', 'exists:companies,id', function ($attribute, $value, $fail) {
                $company = Company::tenantedMyAllCompanies()->where('id', $value)->first();
                if (!$company) $fail('Invalid company');
            }],
            'name' => ['required', function ($attribute, $value, $fail) use ($oldProductBrand) {
                if ($oldProductBrand->name != $value) {
                    $product = ProductBrand::where('company_id', $this->company_id)->where('name', $value)->first();
                    if ($product) $fail('The product brand ' . $value . ' is already in company ' . $product->company->name);
                }
            }]
        ];
    }
}
