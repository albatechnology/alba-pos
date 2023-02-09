<?php

namespace App\Http\Requests;

use App\Models\Company;
use App\Models\ProductVariant;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductVariantRequest extends FormRequest
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
        $oldProductVariant = $this->route('product_variant');
        return [
            'company_id' => ['required', 'exists:companies,id', function ($attribute, $value, $fail) {
                $company = Company::tenantedMyAllCompanies()->where('id', $value)->first();
                if (!$company) $fail('Invalid company');
            }],
            'name' => ['required', function ($attribute, $value, $fail) use ($oldProductVariant) {
                if ($oldProductVariant->name != $value) {
                    $product = ProductVariant::where('company_id', $this->company_id)->where('name', $value)->first();
                    if ($product) $fail('The product variant ' . $value . ' is already in company ' . $product->company->name);
                }
            }],
        ];
    }
}
