<?php

namespace App\Http\Requests;

use App\Models\Company;
use App\Models\PaymentType;
use Illuminate\Foundation\Http\FormRequest;

class StorePaymentTypeRequest extends FormRequest
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
            'payment_category_id' => 'required|exists:payment_categories,id',
            'name' => 'required'
        ];
    }
}
