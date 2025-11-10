<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class OTPPasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'otp' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'otp.required' => 'OTP không được để trống',
            'otp.string' => 'OTP phải là chuỗi',
        ];
    }
}
