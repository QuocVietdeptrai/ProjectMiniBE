<?php

namespace App\Http\Actions\Api\Student;

use App\Domain\Student\UseCase\DeleteStudentUseCase;
use App\Http\Responders\Api\Student\DestroyStudentResponder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class DestroyAction
{
    public function __construct(
        private DeleteStudentUseCase $useCase,
        private DestroyStudentResponder $successResponder,
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