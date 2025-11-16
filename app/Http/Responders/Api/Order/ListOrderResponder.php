<?php

namespace App\Http\Responders\Api\Order;

use App\Enums\StatusCode;
use App\Http\Resources\Api\Order\OrderResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class ListOrderResponder
{
    public function __invoke(LengthAwarePaginator $orders): JsonResponse
    {
        $data = OrderResource::collection($orders)->collection;

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
                'last_page' => $orders->lastPage()
            ]
        ], StatusCode::OK);
    }
}