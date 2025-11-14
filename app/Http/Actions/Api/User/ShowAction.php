<?php 

namespace App\Http\Actions\Api\User;

use App\Domain\User\Usecase\GetUserUseCase;
use App\Enums\StatusCode;
use App\Http\Responders\Api\User\ShowUserResponder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class ShowAction
{
    public function __construct(
        private GetUserUseCase $useCase,
        private ShowUserResponder $responder,
    ) {}
    public function __invoke(int $id): JsonResponse
    {
        try {
            $user = ($this->useCase)($id);
            return ($this->responder)($user);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Người dùng không tồn tại'],StatusCode::NOT_FOUND);
        }
    }
}