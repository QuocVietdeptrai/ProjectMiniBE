<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Student;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Enums\StatusCode;
use App\Http\Actions\Api\Order\DestroyAction;
use App\Http\Actions\Api\Order\IndexAction;
use App\Http\Actions\Api\Order\ShowAction;
use App\Http\Actions\Api\Order\StoreAction;
use App\Http\Actions\Api\Order\UpdateAction;

class OrderController extends Controller
{
    // Lấy danh sách đơn hàng
    public function index(IndexAction $action, Request $request)
    {
        return $action($request);
    }

    // Tạo mới đơn hàng
    public function store(StoreAction $action, StoreOrderRequest $request)
    {
        return $action($request);
    }

    // Lấy chi tiết đơn hàng
    public function show(ShowAction $action, int $id)
    {
        return $action($id);
    }

    // Cập nhật đơn hàng
    public function update(UpdateAction $action, UpdateOrderRequest $request, int $id)
    {
        return $action($request, $id);
    }

    // Xóa đơn hàng
    public function destroy(DestroyAction $action, int $id)
    {
        return $action($id);
    }
}
