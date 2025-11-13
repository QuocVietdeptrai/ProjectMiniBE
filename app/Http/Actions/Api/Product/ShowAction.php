<?php
// File: app/Http/Actions/Api/Product/ShowAction.php

namespace App\Http\Actions\Api\Product;

use App\Domain\Product\UseCase\GetProductUseCase;
use App\Http\Responders\Api\Product\ShowProductResponder;
use App\Http\Responders\Api\Product\ErrorResponder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class ShowAction
{
    public function __construct(
        private GetProductUseCase $useCase,
        private ShowProductResponder $successResponder,
    ) {}

    public function __invoke(int $id): JsonResponse
    {
        try {
            $product = ($this->useCase)($id);
            return ($this->successResponder)($product);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Sản phẩm không tồn tại'], 404);
        }
    }
}