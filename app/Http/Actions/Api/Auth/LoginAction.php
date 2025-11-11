<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\LoginUseCase;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Responders\Api\Auth\LoginResponder;

class LoginAction
{
    public function __construct(
        private LoginUseCase $loginUseCase,
        private LoginResponder $responder
    ) {}

    public function __invoke(LoginRequest $request)
    {
        $authEntity = ($this->loginUseCase)($request);

        // Gọi responder để lấy dữ liệu LoginResource (object)
        $data = ($this->responder)($authEntity);

        // Chuyển LoginResource thành JSON response
        $response = response()->json($data);

        // Tạo cookie cho token
        $cookie = cookie(
            'access_token', $authEntity->token, 60 * 24, '/', null, false, true, false, 'lax'
        );

        // Trả response kèm cookie
        return $response->cookie($cookie);
    }
}
