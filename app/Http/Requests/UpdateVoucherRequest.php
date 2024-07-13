<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVoucherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $voucherId = $this->route('voucher');
        return [
            'voucher' => [
                'required',
                'alpha_num',
                'min:5',
                'max:5',
                Rule::unique('vouchers')->ignore($voucherId),
            ],
        ];
    }
}
