<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStockRequest extends FormRequest
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
        $stock = $this->route('stock');
        return [
            'company_id' => 'required|exists:companies,id',
            'tenant_id' => 'nullable|exists:tenants,id',
            'name' => 'required',
            'email' => 'nullable|email|unique:stocks,email,' . $stock->id,
            'phone' => 'required|numeric|unique:stocks,phone,' . $stock->id,
            'address' => 'nullable',
            'description' => 'nullable',
        ];
    }
}
