<?php

namespace App\Domain\Product\Domain\Entity;

class ProductEntity
{
    public function __construct(
        public ?int $id,
        public string $name,
        public float $price,
        public ?string $description,
        public int $quantity,
        public ?string $image,
        public ?string $created_at = null,
        public ?string $updated_at = null
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'image' => $this->image,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}