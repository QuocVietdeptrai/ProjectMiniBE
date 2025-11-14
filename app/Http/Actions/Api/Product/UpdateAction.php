<?php

namespace App\Http\Actions\Api\Product;

use App\Domain\Product\UseCase\UpdateProductUseCase;
use App\Enums\StatusCode;
use App\Http\Responders\Api\Product\UpdateProductResponder;
use App\Http\Requests\Product\UpdateProductRequest;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class UpdateAction
{
    public function __construct(
        private UpdateProductUseCase $useCase,
        private UpdateProductResponder $successResponder
    ) {}

    public function __invoke(UpdateProductRequest $request, int $id): JsonResponse
    {
        try {
            $product = ($this->useCase)(
                $id,
                $request->only(['name', 'price', 'description', 'quantity']),
                $request->file('image')
            );

            if (!$product) {
                return response()->json(['message' => 'Sản phẩm không tồn tại'], 404);
            }

            return ($this->successResponder)($product);
        } catch (\Exception $e) {
            Log::info('Lỗi cập nhật sản phẩm: ' . $e->getMessage());
            return response()->json(
                ['message' => 'Đã xảy ra lỗi khi cập nhật sản phẩm'],
                StatusCode::INTERNAL_ERR
            );
        }
    }
}
