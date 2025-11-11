<?php

namespace App\Domain\Auth\Exception;

use App\Enums\StatusCode;
use App\Exceptions\Http\HttpException;

class AuthenticationException extends HttpException
{
    private const MESSAGE = 'Unauthorized.';
    private const STATUS_CODE = StatusCode::UNAUTHORIZED;

    public function __construct(
        string $message = self::MESSAGE,
        int $statusCode = self::STATUS_CODE,
        $code = 0,
        \Exception $previous = null
    ) {
        parent::__construct($statusCode, $message, $code, $previous);
    }
}