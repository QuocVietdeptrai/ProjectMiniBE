<?php

namespace App\Http\Requests\Order;

use App\Enums\StatusCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'customer_name' => 'required|string|max:255',
            'class' => 'required|string|max:100',
            'phone' => 'required|string|max:100',
            'order_date' => 'required|date',
            'status' => 'required|in:pending,completed,canceled',
            'payment_method' => 'required|string|in:cash,bank',
            'total' => 'required|numeric|min:0',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.subtotal' => 'required|numeric',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'errors' => $validator->errors()
        ], StatusCode::UNPROCESSABLE_ENTITY));
    }
}
