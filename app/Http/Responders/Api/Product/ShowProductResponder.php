<?php

namespace App\Http\Responders\Api\Product;

use App\Domain\Product\Domain\Entity\ProductEntity;
use App\Enums\StatusCode;
use App\Http\Resources\Api\Product\ProductResource;
use Illuminate\Http\JsonResponse;

class ShowProductResponder
{
    public function __invoke(ProductEntity $product): JsonResponse
    {
        return (new ProductResource($product))
            ->response()
            ->setStatusCode(StatusCode::OK);
    }
}