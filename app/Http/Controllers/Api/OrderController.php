<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Student;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrderController extends Controller
{
	//Lấy ra danh sách đơn hàng
	public function index(Request $request)
	{
		try {
			$query = Order::query();
			if ($request->has('search') && !empty($request->search)) {
				$search = $request->search;
				$query->where(function ($q) use ($search) {
					$q->where('customer_name', 'like', "%{$search}%")
						->orWhere('total', 'like', "%{$search}%")
						->orWhere('payment_method', 'like', "%{$search}%")
						->orWhere('status', 'like', "%{$search}%")
						->orWhereDate('created_at', $search);
				});
			}
			$query->orderBy('created_at', 'desc');
			$orders = $query->paginate(5);

			return response()->json([
				'status' => 'success',
				'data' => $orders
			], 200);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => 'Không thể lấy danh sách đơn hàng.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	// Tạo mới đơn hàng
	public function store(Request $request)
	{
		try {
			$request->validate([
				'customer_name' => 'required|string|max:255',
				'class' => 'required|string|max:100',
				'phone' => 'required|string|max:100',
				'order_date' => 'required|date',
				'status' => 'required|in:pending,completed,canceled',
				'payment_method' => 'required|string|in:cash,bank',
				'total' => 'required|numeric|min:0',
				'products' => 'required|array|min:1',
				'products.*.id' => 'required|exists:products,id',
				'products.*.quantity' => 'required|integer|min:1',
				'products.*.price' => 'required|numeric|min:0',
			]);

			$user = JWTAuth::user();
			$userId = $user->id;

			$student = Student::where('phone', $request->phone)->first();

			if (!$student) {
				$student = Student::create([
					'full_name' => $request->customer_name,
					'class' => $request->class,
					'phone' => $request->phone,
					'email' => null,
					'gender' => null,
					'dob' => null,
					'avatar' => null,
				]);
			}

			$order = Order::create([
				'student_id' => $student->id,
				'user_id' => $userId,
				'customer_name' => $request->customer_name,
				'order_date' => $request->order_date,
				'status' => $request->status,
				'payment_method' => $request->payment_method,
				'total' => $request->total,
			]);


			$student->update(['order_id' => $order->id]);

			foreach ($request->products as $product) {
				OrderItem::create([
					'order_id' => $order->id,
					'product_id' => $product['id'],
					'quantity' => $product['quantity'],
					'price' => $product['price'],
				]);
			}

			return response()->json([
				'status' => 'success',
				'message' => 'Tạo đơn hàng thành công',
				'data' => [
					'order' => $order->load('items.product'),
					'student' => $student
				]
			], 201);
		} catch (Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => 'Có lỗi xảy ra, xem log để biết chi tiết'
			], 500);
		}
	}

	//Lấy chi tiết đơn hàng
	public function show($id)
	{
		try {
			$order = Order::with(['items.product', 'student'])->findOrFail($id);

			$data = [
				'id' => $order->id,
				'customer_name' => $order->customer_name,
				'order_date' => $order->order_date,
				'status' => $order->status,
				'payment_method' => $order->payment_method,
				'total' => $order->total,
				'student' => [
					'full_name' => $order->student->full_name ?? '',
					'class' => $order->student->class ?? '',
					'phone' => $order->student->phone ?? '',
				],
				'items' => $order->items->map(function ($item) {
					return [
						'product_id' => $item->product_id,
						'quantity' => $item->quantity,
						'price' => $item->price,
						'name' => $item->product->name ?? '',
						'image' => $item->product->image ?? null,
					];
				}),
			];

			return response()->json([
				'status' => 'success',
				'data' => $data
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
			$request->validate([
				'customer_name' => 'required|string|max:255',
				'class' => 'required|string|max:100',
				'phone' => 'required|string|max:100',
				'order_date' => 'required|date',
				'status' => 'required|in:pending,completed,canceled',
				'payment_method' => 'required|in:cash,bank',
				'total' => 'required|numeric|min:0',
				'products' => 'required|array|min:1',
				'products.*.id' => 'required|exists:products,id',
				'products.*.quantity' => 'required|integer|min:1',
				'products.*.price' => 'required|numeric|min:0',
			]);

			$order = Order::findOrFail($id);
			$oldStatus = $order->status; // Lưu trạng thái cũ

			// Cập nhật sinh viên
			$student = Student::updateOrCreate(
				['phone' => $request->phone],
				[
					'full_name' => $request->customer_name,
					'class' => $request->class,
				]
			);

			// Cập nhật đơn hàng
			$order->update([
				'customer_name' => $request->customer_name,
				'order_date' => $request->order_date,
				'status' => $request->status,
				'payment_method' => $request->payment_method,
				'total' => $request->total,
				'student_id' => $student->id,
			]);

			$oldItems = $order->items;
			$order->items()->delete();

			// Tạo items mới
			foreach ($request->products as $product) {
				$order->items()->create([
					'product_id' => $product['id'],
					'quantity' => $product['quantity'],
					'price' => $product['price'],
				]);
			}

			// Trừ kho nếu complete
			if ($request->status === 'completed' && $oldStatus !== 'completed') {
				foreach ($request->products as $product) {
					$productModel = Product::find($product['id']);

					if ($productModel->quantity < $product['quantity']) {
						return response()->json([
							'status' => 'error',
							'message' => "Sản phẩm '{$productModel->name}' chỉ còn {$productModel->quantity} trong kho, không đủ để hoàn tất đơn!"
						], 400);
					}

					$productModel->decrement('quantity', $product['quantity']);
				}
			}

			//Hoàn kho nếu hủy
			if ($oldStatus === 'completed' && $request->status !== 'completed') {
				foreach ($oldItems as $item) {
					$productModel = Product::find($item->product_id);
					if ($productModel) {
						$productModel->increment('quantity', $item->quantity);
					}
				}
			}

			// Load dữ liệu trả về
			$order->load('items.product', 'student');

			return response()->json([
				'status' => 'success',
				'message' => 'Cập nhật đơn hàng thành công!',
				'data' => [
					'id' => $order->id,
					'customer_name' => $order->customer_name,
					'order_date' => $order->order_date,
					'status' => $order->status,
					'payment_method' => $order->payment_method,
					'total' => $order->total,
					'student' => [
						'full_name' => $order->student->full_name ?? '',
						'class' => $order->student->class ?? '',
						'phone' => $order->student->phone ?? '',
					],
					'items' => $order->items->map(function ($item) {
						return [
							'product_id' => $item->product_id,
							'quantity' => $item->quantity,
							'price' => $item->price,
							'name' => $item->product->name ?? '',
							'image' => $item->product->image ?? '',
						];
					}),
				]
			], 200);
		} catch (ModelNotFoundException $e) {
			return response()->json([
				'status' => 'error',
				'message' => 'Đơn hàng không tồn tại'
			], 404);
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
		}
	}
}
