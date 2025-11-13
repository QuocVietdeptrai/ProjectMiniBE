<?php

namespace App\Domain\Product\Domain\Repository;
use App\Domain\Product\Domain\Entity\ProductEntity;
use Illuminate\Pagination\LengthAwarePaginator;
interface ProductRepository
{
    public function count(): int;
    public function paginate(?string $search, int $perPage = 4): LengthAwarePaginator;
    public function all(?string $search): array;
    public function create(ProductEntity $entity): ProductEntity;
    public function find(int $id): ?ProductEntity;
    public function update(int $id, ProductEntity $entity): ?ProductEntity;
    public function delete(int $id): bool;
}