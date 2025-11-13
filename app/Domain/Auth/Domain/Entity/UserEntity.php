<?php

namespace App\Domain\Auth\Domain\Entity;

class UserEntity
{
    public function __construct(
        public ?int $id = null,
        public string $name,
        public string $email,
        public string $role,
        public ?string $phone = null,
        public ?string $address = null,
        public ?string $image = null,
        public ?string $created_at = null,
        public ?string $last_login_at = null,
        public ?string $password = null,
        public ?string $status = null, 
        public ?string $otp = null,
        public ?string $otp_expires_at = null ,
    ) {}

    public function toArray(): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'email'         => $this->email,
            'role'          => $this->role,
            'phone'         => $this->phone,
            'address'       => $this->address,
            'image'         => $this->image,
            'created_at'    => $this->created_at,
            'last_login_at' => $this->last_login_at,
            'password'      => $this->password,
            'status'        => $this->status,
        ];
    }
}
