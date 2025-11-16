<?php

namespace App\Domain\Order\Domain\Entity;

class OrderItemEntity
{
    public function __construct(
        public ?int $id,
        public int $orderId,
        public int $productId,
        public int $quantity,
        public float $price,
        public ?string $productName = null,
        public ?string $productImage = null,
    ) {}
}