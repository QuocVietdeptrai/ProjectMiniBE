<?php

namespace App\Http\Responders\Api\Product;

use App\Http\Resources\Api\Product\ProductResource;
use Illuminate\Http\JsonResponse;

class ListOrderProductResponder
{
    public function __invoke($products): JsonResponse
    {
        $data = ProductResource::collection($products)->collection;

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}