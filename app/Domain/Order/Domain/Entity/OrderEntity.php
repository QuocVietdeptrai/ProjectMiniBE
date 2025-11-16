<?php

namespace App\Domain\Order\Domain\Entity;

use Illuminate\Support\Collection;

class OrderEntity
{
    public function __construct(
        public ?int $id,
        public int $studentId,
        public int $userId,
        public string $customerName,
        public string $orderDate,
        public string $status,
        public string $paymentMethod,
        public float $total,
        public ?Collection $items = null,
        public ?object $student = null,
        public ?string $createdAt = null,
        public ?string $updatedAt = null,
    ) {}
}