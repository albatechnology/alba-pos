<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
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
        $supplier = $this->route('supplier');
        return [
            'company_id' => 'required|exists:companies,id',
            'code' => 'required',
            'name' => 'required',
            'email' => 'required|unique:suppliers,email,' . $supplier->id,
            'phone' => 'required|unique:suppliers,phone,' . $supplier->id,
            'address' => 'required',
            'province' => 'required',
            'city' => 'required',
            'description' => 'nullable',
        ];
    }
}
