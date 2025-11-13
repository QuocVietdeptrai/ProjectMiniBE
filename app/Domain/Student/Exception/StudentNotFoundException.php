<?php

namespace App\Domain\Student\Exception;

class StudentNotFoundException extends StudentException
{
    protected $message = 'Sinh viên không tồn tại';
    protected $code = 404;
}