<?php

namespace App\Domain\Student\Domain\Repository;

use App\Domain\Student\Domain\Entity\StudentEntity;
use Illuminate\Pagination\LengthAwarePaginator;

interface StudentRepository
{
    public function count(): int;
    public function paginate(?string $search , int $perPage = 5) : LengthAwarePaginator;
    public function all(?string $search): array;
    public function create(StudentEntity $entity): StudentEntity;
    public function find(int $id): ?StudentEntity;
    public function update(int $id, StudentEntity $entity): ?StudentEntity;
    public function delete(int $id): bool;
}