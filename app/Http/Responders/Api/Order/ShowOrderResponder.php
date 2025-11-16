<?php

namespace App\Http\Responders\Api\Order;

use App\Domain\Order\Domain\Entity\OrderEntity;
use App\Enums\StatusCode;
use App\Http\Resources\Api\Order\OrderResource;
use Illuminate\Http\JsonResponse;

class ShowOrderResponder
{
    public function __invoke(OrderEntity $order): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => new OrderResource($order)
        ], StatusCode::OK);
    }

    public function notFound(string $message): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message
        ], StatusCode::NOT_FOUND);
    }
}