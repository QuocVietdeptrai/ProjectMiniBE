<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'description' => 'nullable|string|max:10000',
            'quantity' => 'nullable|integer|min:0',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }
}
