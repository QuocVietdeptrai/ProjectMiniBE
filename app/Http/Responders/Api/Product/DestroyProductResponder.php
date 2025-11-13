<?php

namespace App\Http\Responders\Api\Product;

use App\Enums\StatusCode;
use Illuminate\Http\JsonResponse;

class DestroyProductResponder
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Xóa sản phẩm thành công'
        ], StatusCode::OK);
    }
}