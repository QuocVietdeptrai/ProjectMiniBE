<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\UpdateProfileUseCase;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Http\Responders\Api\Auth\UserResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Enums\StatusCode;

class UpdateProfileAction
{
    public function __construct(
        private UpdateProfileUseCase $useCase,
        private UserResponder $successResponder
    ) {}

    public function __invoke(UpdateProfileRequest $request): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], StatusCode::UNAUTHORIZED);
            }

            $updatedUser = ($this->useCase)(
                $user->id,
                $request->only(['name', 'email', 'phone', 'address']),
                $request->file('image')
            );

            return ($this->successResponder)($updatedUser)->response(); 

        } catch (\Exception $e) {
            Log::error('Lỗi cập nhật Profile: ' . $e->getMessage());
            return response()->json(
                ['message' => 'Đã xảy ra lỗi khi cập nhật Profile'],
                StatusCode::INTERNAL_ERR
            );
        }
    }
}
