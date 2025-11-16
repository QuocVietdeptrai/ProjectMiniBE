<?php

namespace App\Domain\Order\UseCase;

use App\Domain\Order\Domain\Entity\OrderEntity;
use App\Domain\Order\Domain\Repository\OrderRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetOrderUseCase
{
    public function __construct(protected OrderRepository $repository) {}

    public function execute(int $id): OrderEntity
    {
        $order = $this->repository->findById($id);
        if (!$order) {
            throw new ModelNotFoundException('Đơn hàng không tồn tại');
        }
        return $order;
    }
}