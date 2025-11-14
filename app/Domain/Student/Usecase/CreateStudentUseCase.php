<?php

namespace App\Domain\Student\Usecase;

use App\Domain\Student\Domain\Entity\StudentEntity;
use App\Domain\Student\Domain\Repository\StudentRepository;
use App\Helpers\CloudinaryHelper;

class CreateStudentUseCase
{
    public function __construct(private StudentRepository $repo) {}

    public function __invoke(array $data, $imageFile): StudentEntity
    {
        $imageUrl = null;
        if ($imageFile && $imageFile->isValid()) {
            $imageUrl = CloudinaryHelper::upload($imageFile, 'products');
        }

        $entity = new StudentEntity(
            id: null,
            full_name: $data['full_name'] ?? '',   
            dob: $data['dob'] ?? null,
            gender: $data['gender'] ?? null,
            email: $data['email'] ?? null,
            phone: $data['phone'] ?? null,
            class: $data['class'] ?? null,
            avatar: $imageUrl ?? null,
            created_at: null,
            updated_at: null
        );


        return $this->repo->create($entity);
    }
}