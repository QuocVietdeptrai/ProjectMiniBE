<?php

namespace App\Domain\User\Exception;

use Exception;

class EmailExistsException extends Exception
{
    protected $message = 'Email đã được đăng ký trước đó!';
}
