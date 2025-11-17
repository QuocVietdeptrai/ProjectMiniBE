<?php

namespace App\Domain\Student\Usecase;
use App\Domain\Student\Domain\Repository\StudentRepository;
use App\Domain\Student\Exception\StudentNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeleteStudentUseCase
{
    public function __construct(private StudentRepository $repo) {}

    public function __invoke(int $id): bool
    {
        if (!$this->repo->find($id)) {
            throw new StudentNotFoundException();
        }
        return $this->repo->delete($id);
    }
}