<?php

namespace App\Http\Requests;

use App\Models\Company;
use App\Models\PaymentCategory;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentCategoryRequest extends FormRequest
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
        $oldPaymentCategory = $this->route('payment_category');
        return [
            'company_id' => ['required', 'exists:companies,id', function ($attribute, $value, $fail) {
                $company = Company::tenantedMyAllCompanies()->where('id', $value)->first();
                if (!$company) $fail('Invalid company');
            }],
            'name' => ['required', function ($attribute, $value, $fail) use ($oldPaymentCategory) {
                if ($oldPaymentCategory->name != $value) {
                    $paymentCategory = PaymentCategory::where('company_id', $this->company_id)->where('name', $value)->first();
                    if ($paymentCategory) $fail('The payment category ' . $value . ' is already in company ' . $paymentCategory->company->name);
                }
            }],
            'is_exact_change' => 'nullable',
        ];
    }
}
