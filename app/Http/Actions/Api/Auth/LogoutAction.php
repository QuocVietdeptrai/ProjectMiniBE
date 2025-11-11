<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\LogoutUseCase;
use App\Http\Responders\Api\Auth\MessageResponder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class LogoutAction
{
    public function __construct(
        private LogoutUseCase $useCase,
        private MessageResponder $responder
    ) {}

    public function __invoke(Request $request)
    {
        $token = $request->cookie('access_token');

        if (!$token) {
            return ($this->responder)([
                'code' => 'error',
                'message' => 'Token not found'
            ])->setStatusCode(400);
        }

        // Thực hiện logout logic nếu cần
        $this->useCase->__invoke($token);

        $response = ($this->responder)([
            'code' => 'success',
            'message' => 'Logout successful'
        ]);

        // Xóa cookie access_token
        return $response->response()->withCookie(Cookie::forget('access_token'));
    }
}
