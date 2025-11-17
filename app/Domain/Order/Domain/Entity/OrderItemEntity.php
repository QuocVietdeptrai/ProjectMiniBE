<?php

namespace App\Domain\Order\Domain\Entity;

class OrderItemEntity
{
    public function __construct(
        public ?int $id,
        public int $orderId,
        public int $product_id,
        public int $quantity,
        public float $price,
        public ?float $subtotal = null,
        public ?string $createdAt = null,
        public ?string $updatedAt = null, 
    ) {}
}