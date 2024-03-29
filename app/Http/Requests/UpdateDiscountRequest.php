<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDiscountRequest extends FormRequest
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
        $discount = $this->route('discount');
        return [
            'company_id' => 'required|exists:companies,id',
            'name' => 'required',
            'description' => 'nullable',
            'type' => 'required',
            'value' => 'required|integer',
            'is_active' => 'nullable',
            'date' => 'required',
        ];
    }
}
