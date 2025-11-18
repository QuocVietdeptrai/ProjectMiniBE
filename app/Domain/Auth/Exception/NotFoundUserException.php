<?php

namespace App\Domain\Auth\Exception;

use App\Enums\StatusCode;
use Exception;

class NotFoundUserException extends Exception
{
    protected $message = 'Người dùng không tồn tại!';
    protected $code = StatusCode::UNAUTHORIZED;
}