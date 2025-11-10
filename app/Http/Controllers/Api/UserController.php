<?php

namespace App\Http\Controllers\Api;

use App\Helpers\CloudinaryHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Lấy danh sách người dùng
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(5);

        return response()->json([
            'status' => 'success',
            'data' => $users
        ], 200);
    }

    // Tạo người dùng mới
    public function store(StoreUserRequest $request)
    {
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
                'message' => 'Người dùng không tồn tại',
            ], 404);
        }
    }

    // Cập nhật người dùng
    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $user->image = CloudinaryHelper::upload($request->file('image'), 'users');
            }

            $user->update($request->except('password', 'image'));

            return response()->json([
                'status' => 'success',
                'data' => $user
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Người dùng không tồn tại',
            ], 404);
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
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Xóa người dùng thành công',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Người dùng không tồn tại',
            ], 404);
        }
    }
}
