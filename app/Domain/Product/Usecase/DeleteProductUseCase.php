<?php

namespace App\Domain\Product\Usecase;

use App\Domain\Product\Domain\Repository\ProductRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeleteProductUseCase
{
    public function __construct(private ProductRepository $repo) {}

    public function __invoke(int $id): bool
    {
        if (!$this->repo->find($id)) {
            throw new ModelNotFoundException('Sản phẩm không tồn tại');
        }
        return $this->repo->delete($id);
    }
}