<?php 

namespace App\Http\Actions\Api\User;

use App\Domain\User\Usecase\ListUserUseCase;
use App\Http\Responders\Api\User\ListUserResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IndexAction
{
    public function __construct(
        private ListUserUseCase $useCase,
        private ListUserResponder $responder
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $users = ($this->useCase)($request->search);
        return ($this->responder)($users);
    }
}