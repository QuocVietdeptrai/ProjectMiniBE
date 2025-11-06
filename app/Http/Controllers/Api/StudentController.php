<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Helpers\CloudinaryHelper;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    // Lấy danh sách sinh viên (có phân trang + tìm kiếm theo tên)
    public function index(Request $request)
    {
        $query = Student::query();

        if ($request->has('search')) {
            $query->where('full_name', 'like', "%{$request->search}%");
        }

        $query->orderBy('created_at', 'desc');
        $students = $query->paginate(10); // số lượng trên 1 trang

        return response()->json([
            'status' => 'success',
            'data' => $students
        ], 200);
    }

    // Thêm sinh viên
    public function store(Request $request)
    {
        try {
            $request->validate([
                'full_name' => 'required|string|max:255',
                'dob' => 'required|date',
                'gender' => 'required|in:male,female,other',
                'email' => 'required|email|unique:students,email',
                'phone' => 'nullable|string|max:20',
                'class' => 'nullable|string|max:50',
                'avatar' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            ]);

            $avatarUrl = null;
            if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
                $avatarUrl = CloudinaryHelper::upload($request->file('avatar'), 'students');
            }

            $student = Student::create([
                'full_name' => $request->full_name,
                'dob' => $request->dob,
                'gender' => $request->gender,
                'email' => $request->email,
                'phone' => $request->phone ?? null,
                'class' => $request->class ?? null,
                'avatar' => $avatarUrl,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Thêm sinh viên thành công',
                'data' => $student
            ], 201);

        } catch (Exception $e) {
            Log::error('Lỗi thêm sinh viên: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra, xem log để biết chi tiết'
            ], 500);
        }
    }

    // Xem chi tiết sinh viên
    public function show($id)
    {
        try {
            $student = Student::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $student
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sinh viên không tồn tại',
            ], 404);
        }
    }

    // Cập nhật sinh viên
    public function update(Request $request, $id)
    {
        try {
            $student = Student::findOrFail($id);

            $request->validate([
                'full_name' => 'sometimes|string|max:255',
                'dob' => 'sometimes|date',
                'gender' => 'sometimes|in:male,female,other',
                'email' => 'sometimes|email|unique:students,email,' . $id,
                'phone' => 'nullable|string|max:20',
                'class' => 'nullable|string|max:50',
                'avatar' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            ]);

            if ($request->has('full_name')) $student->full_name = $request->full_name;
            if ($request->has('dob')) $student->dob = $request->dob;
            if ($request->has('gender')) $student->gender = $request->gender;
            if ($request->has('email')) $student->email = $request->email;
            if ($request->has('phone')) $student->phone = $request->phone;
            if ($request->has('class')) $student->class = $request->class;

            if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
                $avatarUrl = CloudinaryHelper::upload($request->file('avatar'), 'students');
                if ($avatarUrl) $student->avatar = $avatarUrl;
            }

            $student->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Cập nhật sinh viên thành công',
                'data' => $student
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sinh viên không tồn tại',
            ], 404);
        } catch (Exception $e) {
            Log::error('Lỗi cập nhật sinh viên: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra, xem log để biết chi tiết',
            ], 500);
        }
    }

    // Xóa sinh viên
    public function destroy($id)
    {
        try {
            $student = Student::findOrFail($id);
            $student->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Xóa sinh viên thành công',
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sinh viên không tồn tại',
            ], 404);
        } catch (Exception $e) {
            Log::error('Lỗi xóa sinh viên: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra, xem log để biết chi tiết',
            ], 500);
        }
    }
}
