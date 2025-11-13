<?php

namespace App\Http\Responders\Api\Product;

use App\Domain\Product\Domain\Entity\ProductEntity;
use App\Enums\StatusCode;
use App\Http\Resources\Api\Product\ProductResource;
use Illuminate\Http\JsonResponse;

class StoreProductResponder
{
    public function __invoke(ProductEntity $product): JsonResponse
    {
        return (new ProductResource($product))
            ->additional(['message' => 'Thêm sản phẩm thành công'])
            ->response()
            ->setStatusCode(StatusCode::CREATED);
    }
}