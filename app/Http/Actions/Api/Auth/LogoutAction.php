<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\LogoutUseCase;
use App\Http\Resources\Api\Auth\MessageResource;
use App\Http\Responders\Api\Auth\MessageResponder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class LogoutAction
{
    public function __construct(
        private LogoutUseCase $useCase,
        private MessageResponder $responder
    ) {}

    public function __invoke(Request $request): MessageResource
    {
        $token = $request->cookie('access_token');

        if (!$token) {
            return (new MessageResource([
                'code' => 'error',
                'message' => 'Token not found'
            ]))->setStatusCode(400);
        }

        $result = ($this->useCase)($token);

        $response = ($this->responder)($result);

        $cookie = Cookie::forget('access_token');

        return $response->withCookie($cookie);
    }
}