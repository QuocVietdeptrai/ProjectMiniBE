<?php

namespace App\Domain\Order\Infrastructure;

use App\Domain\Order\Domain\Repository\OrderRepository;
use App\Models\Order;

class DbOrderInfrastructure implements OrderRepository
{
    public function count(): int
    {
        return Order::count();
    }
}