<?php

namespace App\Http\Responders\Api\Order;

use App\Domain\Order\Domain\Entity\OrderEntity;
use App\Enums\StatusCode;
use App\Http\Resources\Api\Order\OrderResource;
use Illuminate\Http\JsonResponse;

class UpdateOrderResponder
{
    public function __invoke(OrderEntity $order): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật đơn hàng thành công!',
            'data' => new OrderResource($order)
        ], StatusCode::OK);
    }

    public function error(string $message): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message
        ], StatusCode::BAD_REQUEST);
    }
}