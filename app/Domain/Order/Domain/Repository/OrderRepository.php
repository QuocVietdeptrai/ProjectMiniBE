<?php

namespace App\Domain\Order\Domain\Repository;

interface OrderRepository
{
    public function count(): int;
}