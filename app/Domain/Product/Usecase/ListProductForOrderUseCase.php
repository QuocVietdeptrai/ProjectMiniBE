<?php

namespace App\Domain\Product\UseCase;

use App\Domain\Product\Domain\Repository\ProductRepository;

class ListProductForOrderUseCase
{
    public function __construct(private ProductRepository $repo) {}

    public function __invoke(?string $search = null): array
    {
        return $this->repo->all($search);
    }
}