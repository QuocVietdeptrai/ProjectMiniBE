<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Models\Student;
use App\Helpers\CloudinaryHelper;
use Exception;
use App\Enums\StatusCode;
use App\Http\Actions\Api\Student\ShowAction;
use App\Http\Actions\Api\Student\IndexAction;
use App\Http\Actions\Api\Student\UpdateAction;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(IndexAction $action, Request $request)
    {
        return $action($request);
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
            ],  StatusCode::CREATED);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra!'
            ], StatusCode::INTERNAL_ERR);
        }
    }

    // Xem chi tiết sinh viên
    public function show(ShowAction $action, int $id)
    {
        return $action($id);
    }

    public function update(UpdateAction $action, UpdateStudentRequest $request, int $id)
    {
        return $action($request, $id);
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
            ], StatusCode::OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sinh viên không tồn tại',
            ], StatusCode::NOT_FOUND);
        }
    }
}
