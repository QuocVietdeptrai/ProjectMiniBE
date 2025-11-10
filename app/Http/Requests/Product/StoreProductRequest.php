<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Cho phép tất cả, nếu cần kiểm tra quyền thì sửa ở đây
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'quantity' => 'nullable|integer|min:0',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }
}
