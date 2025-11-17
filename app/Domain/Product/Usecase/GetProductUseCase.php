<?php

namespace App\Domain\Product\Usecase;

use App\Domain\Product\Domain\Repository\ProductRepository;
use App\Domain\Product\Domain\Entity\ProductEntity;
use App\Domain\Product\Exception\ProductNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetProductUseCase
{
    public function __construct(private ProductRepository $repo) {}

    public function __invoke(int $id): ProductEntity
    {
        $product = $this->repo->find($id);
        if (!$product) {
            throw new ProductNotFoundException();
        }
        return $product;
    }
}