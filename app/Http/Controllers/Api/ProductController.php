<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use App\Helpers\CloudinaryHelper;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    // Lấy danh sách sản phẩm (có phân trang)
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

    // Lấy tất cả sản phẩm (dùng cho tạo đơn hàng)
    public function indexOrder(Request $request)
    {
        $query = Product::query();

        if ($request->has('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $query->orderBy('created_at', 'desc');
        $products = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $products
        ], 200);
    }

    // Thêm sản phẩm
    public function store(StoreProductRequest $request)
    {
        try {
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

    // Cập nhật sản phẩm
    public function update(UpdateProductRequest $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

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
