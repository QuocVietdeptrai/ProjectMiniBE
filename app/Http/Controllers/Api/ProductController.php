<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Helpers\CloudinaryHelper;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
	//Lấy ra danh sách sản phẩm
	public function index(Request $request)
	{
		$query = Product::query();

		if ($request->has('search')) {
			$query->where('name', 'like', "%{$request->search}%");
		}

		$query->orderBy('created_at', 'desc');
		$products = $query->paginate(4);

		return response()->json([
			'status' => 'success',
			'data' => $products
		], 200);
	}

	// Thêm sản phẩm
	public function store(Request $request)
	{
		try {
			$request->validate([
				'name' => 'required|string',
				'price' => 'required|numeric',
				'description' => 'nullable|string',
				'quantity' => 'nullable|integer',
				'image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
			]);

			$uploadedFileUrl = null;
			if ($request->hasFile('image') && $request->file('image')->isValid()) {
				$uploadedFileUrl = CloudinaryHelper::upload($request->file('image'), 'products');
			}

			$product = Product::create([
				'name' => $request->name,
				'price' => $request->price,
				'description' => $request->description ?? null,
				'quantity' => $request->quantity ?? 0,
				'image' => $uploadedFileUrl,
			]);

			return response()->json([
				'status' => 'success',
				'message' => 'Thêm sản phẩm thành công',
				'data' => $product
			], 201);
		} catch (Exception $e) {
			Log::error('Lỗi tạo sản phẩm: ' . $e->getMessage());
			return response()->json([
				'status' => 'error',
				'message' => 'Có lỗi xảy ra, xem log để biết chi tiết'
			], 500);
		}
	}

	// Xem chi tiết sản phẩm
	public function show($id)
	{
		try {
			$product = Product::findOrFail($id);
			return response()->json([
				'status' => 'success',
				'data' => $product
			], 200);
		} catch (ModelNotFoundException $e) {
			return response()->json([
				'status' => 'error',
				'message' => 'Sản phẩm không tồn tại',
			], 404);
		}
	}

	// Cập nhật
	public function update(Request $request, $id)
	{
		try {
			$product = Product::findOrFail($id);

			$request->validate([
				'name' => 'sometimes|string',
				'price' => 'sometimes|numeric',
				'description' => 'nullable|string',
				'quantity' => 'nullable|integer',
				'image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
			]);


			if ($request->hasFile('image') && $request->file('image')->isValid()) {
				$uploadedFileUrl = CloudinaryHelper::upload($request->file('image'), 'products');
				if ($uploadedFileUrl) $product->image = $uploadedFileUrl;
			}

			$product->update($request->except('image'));

			return response()->json([
				'status' => 'success',
				'message' => 'Cập nhật sản phẩm thành công',
				'data' => $product
			], 200);
		} catch (ModelNotFoundException $e) {
			return response()->json([
				'status' => 'error',
				'message' => 'Sản phẩm không tồn tại',
			], 404);
		}
	}

	// Xóa sản phẩm
	public function destroy($id)
	{
		try {
			$product = Product::findOrFail($id);
			$product->delete();

			return response()->json([
				'status' => 'success',
				'message' => 'Xóa sản phẩm thành công',
			], 200);
		} catch (ModelNotFoundException $e) {
			return response()->json([
				'status' => 'error',
				'message' => 'Sản phẩm không tồn tại',
			], 404);
		}
	}
}
