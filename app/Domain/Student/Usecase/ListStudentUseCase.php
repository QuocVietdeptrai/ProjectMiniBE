<?php 

namespace App\Domain\Student\Usecase;

use App\Domain\Student\Domain\Repository\StudentRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ListStudentUseCase
{
    public function __construct(
        private StudentRepository $repository
    ){}
    public function __invoke(?string $search = null,int $perPage = 5): LengthAwarePaginator
    {
        return $this->repository->paginate($search,$perPage);
    }
}