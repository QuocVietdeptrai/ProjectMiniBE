<?php 

namespace App\Domain\Student\UseCase;

use App\Domain\Student\Domain\Entity\StudentEntity;
use App\Domain\Student\Domain\Repository\StudentRepository;
use App\Helpers\CloudinaryHelper;

class UpdateStudentUseCase{
    public function __construct(private StudentRepository $repo){}

    public function __invoke(int $id, array $data, $imageFile = null): ?StudentEntity
    {
        $student = $this->repo->find($id);
        $updateData = [];

        if(array_key_exists('full_name',$data)) $updateData['full_name'] = $data['full_name'];
        if(array_key_exists('dob',$data)) $updateData['dob'] = $data['dob'];
        if(array_key_exists('gender',$data)) $updateData['gender'] = $data['gender'];
        if(array_key_exists('email',$data)) $updateData['email'] = $data['email'];
        if(array_key_exists('phone',$data)) $updateData['phone'] = $data['phone'];
        if(array_key_exists('class',$data)) $updateData['class'] = $data['class'];

        if($imageFile && $imageFile->isValid()){
            $updateData['avatar'] = CloudinaryHelper::upload($imageFile,'students');
        }

        $updatedEntity = new StudentEntity(
            id: $id,
            full_name: $updateData['full_name'] ?? $student->full_name,
            dob: $updateData['dob'] ?? $student->dob,
            gender: $updateData['gender'] ?? $student->gender,
            email: $updateData['email'] ?? $student->email,
            phone: $updateData['phone'] ?? $student->phone,
            class: $updateData['class'] ?? $student->class,
            avatar: $updateData['avatar'] ?? $student->avatar,
            created_at: $student->created_at,
            updated_at: $student->updated_at
        );
        return $this->repo->update($id,$updatedEntity);
    }
}