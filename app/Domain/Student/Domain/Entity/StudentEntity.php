<?php 

namespace App\Domain\Student\Domain\Entity;

class StudentEntity
{
    public function __construct(
        public ?int $id,
        public string $full_name,
        public ?string $dob = null,
        public ?string $gender = null,
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $class = null,
        public ?string $avatar = null,
        public ?string $created_at = null,
        public ?string $updated_at = null
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'dob' => $this->dob,
            'gender' => $this->gender,
            'email' => $this->email,
            'phone' => $this->phone,
            'class' => $this->class,
            'avatar' => $this->avatar,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}