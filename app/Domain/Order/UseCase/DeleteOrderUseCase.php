<?php

namespace App\Domain\Order\UseCase;

use App\Domain\Order\Domain\Repository\OrderRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeleteOrderUseCase
{
    public function __construct(protected OrderRepository $repository) {}

    public function execute(int $id): bool
    {
        if (!$this->repository->findById($id)) {
            throw new ModelNotFoundException('Đơn hàng không tồn tại');
        }
        return $this->repository->delete($id);
    }
}