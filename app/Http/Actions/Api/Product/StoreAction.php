<?php
// File: app/Http/Actions/Api/Product/StoreAction.php

namespace App\Http\Actions\Api\Product;

use App\Domain\Product\UseCase\CreateProductUseCase;
use App\Http\Responders\Api\Product\StoreProductResponder;
use App\Http\Responders\Api\Product\ErrorResponder;
use App\Http\Requests\Product\StoreProductRequest;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\Log;

class StoreAction
{
    public function __construct(
        private CreateProductUseCase $useCase,
        private StoreProductResponder $successResponder,
    ) {}

    public function __invoke(StoreProductRequest $request): JsonResponse
    {
        try {
            $product = ($this->useCase)(
                $request->only(['name', 'price', 'description', 'quantity']),
                $request->file('image')
            );

            return ($this->successResponder)($product);
        } catch (Exception $e) {
            Log::error('Lỗi tạo sản phẩm: ' . $e->getMessage());
            return response()->json(['message' => 'Đã xảy ra lỗi khi tạo sản phẩm'], 500);
        }
    }
}