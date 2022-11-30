<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBankAccountRequest extends FormRequest
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
        $bankAccount = $this->route('bank_account');
        return [
            'account_number' => 'required|unique:bank_accounts,account_number,' . $bankAccount->id,
            'account_name' => 'required',
            'bank_name' => 'required',
        ];
    }
}
