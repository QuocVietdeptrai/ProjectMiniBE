<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Helpers\CloudinaryHelper;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StudentController extends Controller
{

	//Lấy ra danh sách sinh viên
	public function index(Request $request)
	{
		$query = Student::query();

		if ($request->has('search')) {
			$query->where('full_name', 'like', "%{$request->search}%");
		}

		$query->orderBy('created_at', 'desc');
		$students = $query->paginate(5); // số lượng trên 1 trang

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
				'gender' => 'required|string',
				'email' => 'required|email|unique:students,email',
				'phone' => 'nullable|string|max:20',
				'class' => 'nullable|string|max:50',
				'avatar' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
			]);

			$avatarUrl = null;
			if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
				$avatarUrl = CloudinaryHelper::upload($request->file('avatar'), 'products');
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
				'code' => 'success',
				'message' => 'Thêm sinh viên thành công',
				'data' => $student
			], 201);
		} catch (Exception $e) {
			return response()->json([
				'code' => 'error'
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
			$request->validate([
				'full_name' => 'required|string|max:255',
				'dob' => 'required|date',
				'gender' => 'required|string',
				'email' => 'required|email|unique:students,email,' . $id,
				'class' => 'required|string|max:50',
				'avatar' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
			]);

			$student = Student::findOrFail($id);

			if ($request->hasFile('avatar')) {
				$avatarUrl = CloudinaryHelper::upload($request->file('avatar'), 'students');
				$student->avatar = $avatarUrl;
			}

			$student->update($request->except('avatar'));

			return response()->json([
				'status' => 'success',
				'data' => $student
			]);
		} catch (Exception $e) {
			return response()->json(['status' => 'error', 'message' => 'Có lỗi xảy ra!'], 500);
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
