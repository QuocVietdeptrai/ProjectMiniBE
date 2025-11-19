<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\Exception\CheckEmailPasswordException;
use App\Domain\Auth\Exception\CheckPass;
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
        try {
            $authEntity = ($this->loginUseCase)($request);

            // Gọi responder để lấy dữ liệu LoginResource (object)
            $data = ($this->responder)($authEntity);

            // Chuyển LoginResource thành JSON response
            $response = response()->json($data);

            // Tạo cookie cho token
            $cookie = cookie(
                'access_token',
                $authEntity->token,
                60 * 24,
                '/',                          // path
                'projectminibe.onrender.com',  // domain của backend
                true,                          // secure: true khi dùng HTTPS
                true,                          // httpOnly
                false,
                'none'                         // sameSite: 'none' để cross-site cookie
            );


            // Trả response kèm cookie
            return $response->cookie($cookie);
        } catch (CheckEmailPasswordException $e) {
             return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ], $e->getCode());
        }
    }
}
