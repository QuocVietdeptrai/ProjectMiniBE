<?php

namespace App\Http\Responders\Api\User;

use App\Enums\StatusCode;
use Illuminate\Http\JsonResponse;

class DestroyUserResponder
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Xóa người dùng thành công'
        ], StatusCode::OK);
    }
}