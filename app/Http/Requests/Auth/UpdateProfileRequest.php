<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string|max:255',
            'image' => 'sometimes|file|mimes:jpg,jpeg,png|max:5120', // 5MB
        ];
    }

    public function messages()
    {
        return [
            'name.string' => 'Tên phải là chuỗi ký tự',
            'name.max' => 'Tên tối đa 255 ký tự',
            'phone.string' => 'Số điện thoại phải là chuỗi',
            'phone.max' => 'Số điện thoại tối đa 20 ký tự',
            'address.string' => 'Địa chỉ phải là chuỗi',
            'address.max' => 'Địa chỉ tối đa 255 ký tự',
            'image.file' => 'Avatar phải là file hợp lệ',
            'image.mimes' => 'Avatar chỉ chấp nhận jpg, jpeg, png',
            'image.max' => 'Avatar tối đa 5MB',
        ];
    }
}
