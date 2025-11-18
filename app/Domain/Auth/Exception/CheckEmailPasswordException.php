<?php

namespace App\Domain\Auth\Exception;

use App\Enums\StatusCode;
use Exception;

class CheckEmailPasswordException extends Exception
{
    protected $message = 'Email hoặc mật khẩu không đúng!';
    protected $code = StatusCode::UNAUTHORIZED;
}