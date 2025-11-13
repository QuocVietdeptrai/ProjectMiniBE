<?php

namespace App\Domain\Product\UseCase;

use App\Domain\Product\Domain\Repository\ProductRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ListProductUseCase
{
    public function __construct(
        private ProductRepository $repository
    ) {}

    public function __invoke(?string $search = null, int $perPage = 4): LengthAwarePaginator
    {
        return $this->repository->paginate($search, $perPage);
    }
}