<?php 

namespace App\Domain\User\Domain\Repository;

use App\Domain\Auth\Domain\Entity\UserEntity;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepository
{
    public function count(): int;
    public function paginate(?string $search, int $perPage = 4): LengthAwarePaginator;
    public function all(?string $search): array;
    public function create(UserEntity $entity): UserEntity;
    public function find(int $id): ?UserEntity;
    public function update(int $id, UserEntity $entity): ?UserEntity;
    public function delete(int $id): bool;
}