<?php

namespace App\Domain\Student\Exception;

use Exception;

class StudentException extends Exception
{
    protected $message = 'Lỗi hệ thống sinh viên';
    protected $code = 500;

    public function __construct(string $message = null, int $code = null, Exception $previous = null)
    {
        parent::__construct($message ?? $this->message, $code ?? $this->code, $previous);
    }
}