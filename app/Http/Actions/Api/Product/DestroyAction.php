<?php
// File: app/Http/Actions/Api/Product/DestroyAction.php

namespace App\Http\Actions\Api\Product;

use App\Domain\Product\UseCase\DeleteProductUseCase;
use App\Http\Responders\Api\Product\DestroyProductResponder;
use App\Http\Responders\Api\Product\ErrorResponder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class DestroyAction
{
    public function __construct(
        private DeleteProductUseCase $useCase,
        private DestroyProductResponder $successResponder,
    ) {}

    public function __invoke(int $id): JsonResponse
    {
        try {
            ($this->useCase)($id);
            return ($this->successResponder)();
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Sản phẩm không tồn tại'], 404);
        }
    }
}