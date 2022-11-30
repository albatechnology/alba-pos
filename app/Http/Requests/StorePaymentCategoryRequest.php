<?php

namespace App\Http\Requests;

use App\Models\Company;
use App\Models\PaymentCategory;
use Illuminate\Foundation\Http\FormRequest;

class StorePaymentCategoryRequest extends FormRequest
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
                foreach (arrayFilterAndReindex($this->company_ids) as $company_id) {
                    $paymentCategory = PaymentCategory::where('company_id', $company_id)->where('name', $value)->first();
                    if ($paymentCategory) $fail('The payment category ' . $value . ' is already in company ' . $paymentCategory->company->name);
                }
            }],
            'company_ids' => ['required', 'array', function ($attribute, $value, $fail) {
                $company_ids = arrayFilterAndReindex($value);
                $companies = Company::tenantedMyAllCompanies()->whereIn('id', $company_ids)->count();
                if ($companies < count($company_ids)) $fail('Invalid company');
            }],
            'is_exact_change' => 'nullable',
        ];
    }
}
