<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Cho phép tất cả
    }

    public function rules()
    {
        return [
            'full_name' => 'required|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|string',
            'email' => 'required|email|unique:students,email',
            'phone' => 'nullable|string|max:20',
            'class' => 'nullable|string|max:50',
            'avatar' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }
}
