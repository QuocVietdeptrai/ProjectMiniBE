<?php

namespace App\Domain\Student\Exception;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\MessageBag;

class StudentValidationException extends ValidationException
{
    public function __construct(MessageBag $errors, string $message = 'Dữ liệu không hợp lệ')
    {
        parent::__construct($this->validator($errors));
        $this->message = $message;
    }

    private function validator(MessageBag $errors)
    {
        $factory = app(\Illuminate\Validation\Factory::class);
        return $factory->make([], [], [])->setMessageBag($errors);
    }
}