<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStockRequest extends FormRequest
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
            'company_id' => 'required|exists:companies,id',
            'tenant_id' => 'nullable|exists:tenants,id',
            'name' => 'required',
            'email' => 'nullable|unique:stocks,email|email',
            'phone' => 'required|unique:stocks,phone|numeric',
            'address' => 'nullable',
            'description' => 'nullable',
        ];
    }
}
