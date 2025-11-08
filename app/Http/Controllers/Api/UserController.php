<?php

namespace App\Http\Controllers\Api;

use App\Helpers\CloudinaryHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
	// Lấy danh sách người dùng
	public function index(Request $request)
	{
		$query = User::query();

		if ($request->has('search')) {
			$query->where('name', 'like', "%{$request->search}%");
		}

		$query->orderBy('created_at', 'desc');
		$users = $query->paginate(5); // số lượng trên 1 trang

		return response()->json([
			'status' => 'success',
			'data' => $users
		], 200);
	}

	// Tạo người dùng mới
	public function store(Request $request)
	{
		$request->validate([
			'name' => 'required|string|max:255',
			'email' => 'required|email|unique:users',
			'password' => 'required|string|min:6',
			'role' => 'required|string',
			'phone' => 'nullable|string',
			'address' => 'nullable|string',
			'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
			'status' => 'required|in:active,inactive',
		]);

		$imagePath = null;
		if ($request->hasFile('image') && $request->file('image')->isValid()) {
			$imagePath = CloudinaryHelper::upload($request->file('image'), 'users');
		}

		$user = User::create([
			'name' => $request->name,
			'email' => $request->email,
			'password' => Hash::make($request->password),
			'role' => $request->role,
			'phone' => $request->phone,
			'address' => $request->address,
			'image' => $imagePath,
			'status' => $request->status,
		]);

		return response()->json([
			'status' => 'success',
			'data' => $user
		], 201);
	}


	// Xem chi tiết người dùng
	public function show($id)
	{
		try {
			$user = User::findOrFail($id);
			return response()->json([
				'status' => 'success',
				'data' => $user
			], 200);
		} catch (ModelNotFoundException $e) {
			return response()->json([
				'status' => 'error',
				'message' => 'Sản phẩm không tồn tại',
			], 404);
		}
	}


	public function update(Request $request, $id)
	{
		try {
			$user = User::find($id);
			if (!$user) {
				return response()->json(['status' => 'error', 'message' => 'Không tìm thấy'], 404);
			}

			// Validate
			$request->validate([
				'name' => 'sometimes|required|string|max:255',
				'email' => 'sometimes|required|email|unique:users,email,' . $id,
				'password' => 'sometimes|nullable|string|min:6',
				'role' => 'sometimes|required|string',
				'phone' => 'nullable|string|max:20',
				'address' => 'nullable|string|max:500',
				'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
				'status' => 'required|in:active,inactive',
			]);
			if ($request->filled('password')) {
				$user->password = Hash::make($request->password);
			}

			// Xử lý ảnh
			if ($request->hasFile('image') && $request->file('image')->isValid()) {
				$result = CloudinaryHelper::upload($request->file('image'), 'users');
				$user->image = $result;
			}
			$user->update($request->except('image'));
			return response()->json([
				'status' => 'success',
				'data' => $user
			]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => 'Lỗi server: ' . $e->getMessage()
			], 500);
		}
	}

	// Xóa người dùng
	public function destroy($id)
	{
		$user = User::find($id);
		if (!$user) {
			return response()->json(['message' => 'Người dùng không tồn tại'], 404);
		}

		$user->delete();
		return response()->json(['message' => 'Xóa người dùng thành công']);
	}
}
