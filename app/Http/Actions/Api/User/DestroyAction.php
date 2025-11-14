<?php

namespace App\Http\Actions\Api\User;

use App\Domain\User\Usecase\DeleteUserUseCase;
use App\Enums\StatusCode;
use App\Http\Responders\Api\User\DestroyUserResponder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class DestroyAction
{
    public function __construct(
        private DeleteUserUseCase $useCase,
        private DestroyUserResponder $successResponder,
    ) {}

    public function __invoke(int $id): JsonResponse
    {
        try {
            ($this->useCase)($id);
            return ($this->successResponder)();
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Người dùng không tồn tại'], StatusCode::NOT_FOUND);
        }
    }
}