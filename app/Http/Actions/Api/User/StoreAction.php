<?php


namespace App\Http\Actions\Api\User;

use App\Domain\User\Exception\EmailExistsException;
use App\Domain\User\Usecase\CreateUserUseCase;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Responders\Api\User\StoreUserResponder;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class StoreAction
{
    public function __construct(
        private CreateUserUseCase $useCase,
        private StoreUserResponder $successResponder,
    ) {}

    public function __invoke(StoreUserRequest $request): JsonResponse
    {
        try {
            $product = ($this->useCase)(
                $request->only(['name', 'email', 'phone', 'address','password','role','status']),
                $request->file('image')
            );

            return ($this->successResponder)($product);
        } catch (EmailExistsException $e) {
            Log::error('Lỗi tạo người dùng: ' . $e->getMessage());
            return response()->json(['message' => 'Đã xảy ra lỗi khi tạo sản phẩm'], 500);
        }
    }
}