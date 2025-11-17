<?php

namespace App\Domain\Order\Domain\Repository;

use App\Domain\Order\Domain\Entity\OrderEntity;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderRepository
{
    public function count(): int;
    public function findById(int $id): ?OrderEntity;
    public function paginate(?string $search, int $perPage = 4): LengthAwarePaginator;
    public function create(array $data): OrderEntity;
    public function update(int $id, array $data): OrderEntity;
    public function delete(int $id): bool;
}