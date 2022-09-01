<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
        $customer = $this->route('customer');
        return [
            'company_id' => 'required|exists:companies,id',
            'tenant_id' => 'nullable|exists:tenants,id',
            'name' => 'required',
            'email' => 'nullable|email|unique:customers,email,' . $customer->id,
            'phone' => 'required|numeric|unique:customers,phone,' . $customer->id,
            'address' => 'nullable',
            'description' => 'nullable',
        ];
    }
}
