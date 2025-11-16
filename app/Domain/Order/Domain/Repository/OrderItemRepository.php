<?php

namespace App\Domain\Order\Domain\Repository;

use Illuminate\Support\Collection;

interface OrderItemRepository
{
    public function createMany(int $orderId, array $items): Collection;
    public function deleteByOrderId(int $orderId): bool;
    public function getByOrderId(int $orderId): Collection;
}