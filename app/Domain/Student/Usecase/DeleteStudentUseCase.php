<?php

namespace App\Domain\Student\UseCase;
use App\Domain\Student\Domain\Repository\StudentRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeleteStudentUseCase
{
    public function __construct(private StudentRepository $repo) {}

    public function __invoke(int $id): bool
    {
        if (!$this->repo->find($id)) {
            throw new ModelNotFoundException('Sản phẩm không tồn tại');
        }
        return $this->repo->delete($id);
    }
}