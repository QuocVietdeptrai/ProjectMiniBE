<?php 

namespace App\Domain\Student\Usecase;

use App\Domain\Student\Domain\Entity\StudentEntity;
use App\Domain\Student\Domain\Repository\StudentRepository;
use App\Domain\Student\Exception\StudentNotFoundException;

class GetStudentUseCase
{
    public function __construct(private StudentRepository $repository) {}
    public function __invoke(int $id): StudentEntity
    {
        $student = $this->repository->find($id);
        if(!$student){
            throw new StudentNotFoundException('Học sinh không tồn tại!');
        }
        return $student;
    }
}