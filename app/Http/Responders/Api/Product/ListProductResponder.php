<?php
// File: app/Http/Responders/Api/Product/ListProductResponder.php

namespace App\Http\Responders\Api\Product;

use App\Enums\StatusCode;
use App\Http\Resources\Api\Product\ProductResource;
use Illuminate\Http\JsonResponse;

class ListProductResponder
{
    public function __invoke($products): JsonResponse
    {
        $data = ProductResource::collection($products)->collection;

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'pagination' => [
                'current_page' => $products->currentPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'last_page' => $products->lastPage(),
            ]
        ], StatusCode::OK);
    }
}