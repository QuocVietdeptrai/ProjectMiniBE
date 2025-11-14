<?php

namespace App\Http\Actions\Api\User;

use App\Domain\User\Usecase\UpdateUserUseCase;
use App\Enums\StatusCode;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Responders\Api\User\UpdateUserResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class UpdateAction
{
    public function __construct(
        private UpdateUserUseCase $useCase,
        private UpdateUserResponder $successResponder
    ) {}

    public function __invoke(UpdateUserRequest $request, int $id): JsonResponse
    {
        try {
            $product = ($this->useCase)(
                $id,
                $request->only(['name', 'email', 'phone', 'address','password','role','status']),
                $request->file('image')
            );

            if (!$product) {
                return response()->json(['message' => 'Sản phẩm không tồn tại'], StatusCode::NOT_FOUND);
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
