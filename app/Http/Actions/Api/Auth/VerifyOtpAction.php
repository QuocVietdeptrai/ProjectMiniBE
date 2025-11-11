<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\VerifyOtpUseCase;
use App\Http\Requests\Auth\OTPPasswordRequest;
use App\Http\Resources\Api\Auth\MessageResource;
use App\Http\Responders\Api\Auth\MessageResponder;

class VerifyOtpAction
{
    public function __construct(
        private VerifyOtpUseCase $useCase,
        private MessageResponder $responder
    ) {}

    public function __invoke(OTPPasswordRequest $request): MessageResource
    {
        $result = ($this->useCase)($request->otp);
        return ($this->responder)($result);
    }
}