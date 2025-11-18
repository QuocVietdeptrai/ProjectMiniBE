<?php

namespace App\Domain\Auth\Exception;

use App\Enums\StatusCode;
use Exception;

class TokenExpiredException extends Exception
{
    protected $message = 'Token đã hết hạn!';
    protected $code = StatusCode::UNAUTHORIZED;
}