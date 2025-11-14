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
use App\Enums\StatusCode;
use App\Http\Actions\Api\User\DestroyAction;
use App\Http\Actions\Api\User\IndexAction;
use App\Http\Actions\Api\User\ShowAction;
use App\Http\Actions\Api\User\StoreAction;

class UserController extends Controller
{
    // Lấy danh sách người dùng
    public function index(IndexAction $action, Request $request)
    {
        return $action($request);
    }

    // Tạo người dùng mới
    public function store(StoreAction $action, StoreUserRequest $request)
    {
        return $action($request);
    }

    // Xem chi tiết người dùng
    public function show(ShowAction $action, int $id)
    {
        return $action($id);
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
            ], StatusCode::NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi server: ' . $e->getMessage()
            ], StatusCode::INTERNAL_ERR);
        }
    }

    // Xóa người dùng
    public function destroy(DestroyAction $action, int $id)
    {
        return $action($id);
    }
}
