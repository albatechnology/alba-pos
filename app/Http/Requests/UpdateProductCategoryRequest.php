<?php

namespace App\Http\Requests;

use App\Models\Company;
use App\Models\ProductCategory;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductCategoryRequest extends FormRequest
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
        $oldProductCategory = $this->route('product_category');
        return [
            'company_id' => ['required', 'exists:companies,id', function ($attribute, $value, $fail) {
                $company = Company::tenantedMyCompanies()->where('id', $value)->first();
                if (!$company) $fail('Invalid company');
            }],
            'name' => ['required', function ($attribute, $value, $fail) use ($oldProductCategory) {
                if ($oldProductCategory->name != $value) {
                    $product = ProductCategory::where('company_id', $this->company_id)->where('name', $value)->first();
                    if ($product) $fail('The product ' . $value . ' is already in company ' . $product->company->name);
                }
            }]
        ];
    }
}
