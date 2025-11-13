<?php

namespace App\Domain\Product\UseCase;

use App\Domain\Product\Domain\Entity\ProductEntity;
use App\Domain\Product\Domain\Repository\ProductRepository;
use App\Helpers\CloudinaryHelper;
use Exception;

class CreateProductUseCase
{
    public function __construct(private ProductRepository $repo) {}

    public function __invoke(array $data, $imageFile): ProductEntity
    {
        $imageUrl = null;
        if ($imageFile && $imageFile->isValid()) {
            $imageUrl = CloudinaryHelper::upload($imageFile, 'products');
        }

        $entity = new ProductEntity(
            id: null,
            name: $data['name'],
            price: (float) $data['price'],
            description: $data['description'] ?? null,
            quantity: (int) ($data['quantity'] ?? 0),
            image: $imageUrl
        );

        return $this->repo->create($entity);
    }
}