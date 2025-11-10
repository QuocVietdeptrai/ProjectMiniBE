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
            'dob' => 'required|date',
            'gender' => 'required|string',
            'email' => 'required|email|unique:students,email,' . $studentId,
            'phone' => 'nullable|string|max:20',
            'class' => 'required|string|max:50',
            'avatar' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }
}
