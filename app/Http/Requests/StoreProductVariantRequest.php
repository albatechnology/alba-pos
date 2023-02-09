<?php

namespace App\Http\Requests;

use App\Models\Company;
use App\Models\ProductVariant;
use App\Enums\ProductVariantType;
use BenSampo\Enum\Rules\EnumKey;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductVariantRequest extends FormRequest
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
            'company_ids' => ['required', 'array', function ($attribute, $value, $fail) {
                $company_ids = arrayFilterAndReindex($value);
                $companies = Company::tenantedMyAllCompanies()->whereIn('id', $company_ids)->count();
                if ($companies < count($company_ids)) $fail('Invalid company');
            }],
            'name' => ['required', function ($attribute, $value, $fail) {
                foreach (arrayFilterAndReindex($this->company_ids) as $company_id) {
                    $product = ProductVariant::where('company_id', $company_id)->where('name', $value)->first();
                    if ($product) $fail('The product variant' . $value . ' is already in company ' . $product->company->name);
                }
            }],
            // 'selection_type' => ['required', new EnumKey(ProductVariantType::class)],
        ];
    }
}
