<?php

namespace App\Domain\Product\Infrastructure;
use App\Domain\Product\Domain\Repository\ProductRepository;
use App\Models\Product;

class DbProductInfrastructure implements ProductRepository
{
    public function count(): int
    {
        return Product::count();
    }
}