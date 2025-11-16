<?php

namespace App\Http\Responders\Api\Order;

use App\Enums\StatusCode;
use Illuminate\Http\JsonResponse;

class DestroyOrderResponder
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Xóa đơn hàng thành công'
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