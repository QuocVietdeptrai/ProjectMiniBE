<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\CheckAuthUseCase;
use App\Http\Resources\Api\Auth\MessageResource;
use App\Http\Resources\Api\Auth\UserResource;
use App\Http\Responders\Api\Auth\UserResponder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckAuthAction
{
    public function __construct(
        private CheckAuthUseCase $useCase,
        private UserResponder $responder
    ) {}

    public function __invoke(Request $request): UserResource
    {
        $token = $request->cookie('access_token');
        Log::info('CheckAuthAction: token='.$token);
        if (!$token) {
            return (new MessageResource([
                'code' => 'error',
                'message' => 'Token not found'
            ]))->setStatusCode(400);
        }

        $userEntity = ($this->useCase)($token);
        return ($this->responder)($userEntity);
    }
}