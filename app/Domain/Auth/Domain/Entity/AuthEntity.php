<?php

namespace App\Domain\Auth\Domain\Entity;

class AuthEntity
{
    public function __construct(
        public string $token,
        public ?UserEntity $user = null,
        public string $message = ''
    ) {}
}