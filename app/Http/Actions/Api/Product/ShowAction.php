<?php

namespace App\Http\Actions\Api\Product;

use App\Domain\Product\Exception\ProductNotFoundException;
use App\Domain\Product\UseCase\GetProductUseCase;
use App\Enums\StatusCode;
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
        } catch (ProductNotFoundException $e) {
             return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ], $e->getCode());
        }
    }
}