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
            'name' => 'sometimes|nullable|string|max:255',
            'price' => 'sometimes|nullable|numeric|min:0',
            'description' => 'sometimes|nullable|string|max:10000',
            'quantity' => 'sometimes|nullable|integer|min:0',
            'image' => 'sometimes|nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }

}
