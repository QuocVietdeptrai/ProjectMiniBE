<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $studentId = $this->route('student'); // lấy id từ route
        return [
            'full_name' => 'required|string|max:255',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|max:50',
            'email' => 'nullable|email|unique:students,email,' . $studentId,
            'phone' => 'nullable|string|max:255',
            'class' => 'nullable|string|max:255',
            'avatar' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }
}
