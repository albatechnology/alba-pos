<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
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
            'code' => 'required|unique:suppliers,code',
            'name' => 'required',
            'email' => 'required|unique:suppliers,email|email',
            'phone' => 'required|unique:suppliers,phone|numeric',
            'address' => 'required',
            'province_id' => 'nullable',
            'city_id' => 'nullable',
            'district_id' => 'nullable',
            'village_id' => 'nullable',
            'description' => 'nullable',

            'account_number' => 'required',
            'account_name' => 'required',
            'bank_name' => 'required'
        ];
    }
}
