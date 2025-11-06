<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    // Lấy danh sách đơn hàng (có phân trang + search theo customer_name, phone, status)
    public function index(Request $request)
    {
        $query = Order::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");
            });
        }

        $query->orderBy('order_date', 'desc');
        $orders = $query->paginate(5); // phân trang 5 bản ghi

        return response()->json([
            'status' => 'success',
            'data' => $orders
        ], 200);
    }

    // Tạo mới đơn hàng
    public function store(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'customer_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:255',
                'order_date' => 'required|date',
                'delivery_date' => 'nullable|date|after_or_equal:order_date',
                'status' => 'required|in:pending,completed,canceled',
                'payment_method' => 'required|string|max:50',
                'total' => 'required|numeric|min:0',
            ]);

            $order = Order::create($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Tạo đơn hàng thành công',
                'data' => $order
            ], 201);

        } catch (Exception $e) {
            Log::error('Lỗi tạo đơn hàng: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra, xem log để biết chi tiết'
            ], 500);
        }
    }

    // Xem chi tiết đơn hàng
    public function show($id)
    {
        try {
            $order = Order::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $order
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Đơn hàng không tồn tại'
            ], 404);
        }
    }

    // Cập nhật đơn hàng
    public function update(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);

            $request->validate([
                'user_id' => 'sometimes|exists:users,id',
                'customer_name' => 'sometimes|string|max:255',
                'phone' => 'sometimes|string|max:20',
                'address' => 'sometimes|string|max:255',
                'order_date' => 'sometimes|date',
                'delivery_date' => 'nullable|date|after_or_equal:order_date',
                'status' => 'sometimes|in:pending,completed,canceled',
                'payment_method' => 'sometimes|string|max:50',
                'total' => 'sometimes|numeric|min:0',
            ]);

            foreach ($request->only([
                'user_id', 'customer_name', 'phone', 'address', 
                'order_date', 'delivery_date', 'status', 'payment_method', 'total'
            ]) as $key => $value) {
                $order->$key = $value;
            }

            $order->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Cập nhật đơn hàng thành công',
                'data' => $order
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Đơn hàng không tồn tại'
            ], 404);
        } catch (Exception $e) {
            Log::error('Lỗi cập nhật đơn hàng: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra, xem log để biết chi tiết'
            ], 500);
        }
    }

    // Xóa đơn hàng
    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Xóa đơn hàng thành công'
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Đơn hàng không tồn tại'
            ], 404);
        } catch (Exception $e) {
            Log::error('Lỗi xóa đơn hàng: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra, xem log để biết chi tiết'
            ], 500);
        }
    }
}
