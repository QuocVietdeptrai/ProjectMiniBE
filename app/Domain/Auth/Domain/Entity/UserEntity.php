<?php

namespace App\Domain\Auth\Domain\Entity;

class UserEntity
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public string $role,
        public ?string $phone = null,
        public ?string $address = null,
        public ?string $image = null,
        public string $created_at,
    ) {}
}