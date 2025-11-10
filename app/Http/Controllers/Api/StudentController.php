<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Models\Student;
use App\Helpers\CloudinaryHelper;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StudentController extends Controller
{
    // Lấy danh sách sinh viên
    public function index()
    {
        $students = Student::orderBy('created_at', 'desc')->paginate(5);
        return response()->json([
            'status' => 'success',
            'data' => $students
        ], 200);
    }

    // Thêm sinh viên
    public function store(StoreStudentRequest $request)
    {
        try {
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
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra!'
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
    public function update(UpdateStudentRequest $request, $id)
    {
        try {
            $student = Student::findOrFail($id);

            if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
                $avatarUrl = CloudinaryHelper::upload($request->file('avatar'), 'students');
                $student->avatar = $avatarUrl;
            }

            $student->update($request->except('avatar'));

            return response()->json([
                'status' => 'success',
                'data' => $student
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sinh viên không tồn tại',
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra!',
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
        }
    }
}
