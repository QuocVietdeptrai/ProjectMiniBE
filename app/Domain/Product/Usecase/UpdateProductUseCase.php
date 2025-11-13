<?php

namespace App\Domain\Product\UseCase;

use App\Domain\Product\Domain\Entity\ProductEntity;
use App\Domain\Product\Domain\Repository\ProductRepository;
use App\Helpers\CloudinaryHelper;
class UpdateProductUseCase
{
    public function __construct(private ProductRepository $repo) {}

    public function __invoke(int $id, array $data, $imageFile = null): ?ProductEntity
    {
        $product = $this->repo->find($id);
        $updateData = [];

        if (array_key_exists('name', $data)) $updateData['name'] = $data['name'];
        if (array_key_exists('price', $data))$updateData['price'] = (float) $data['price'];
        if (array_key_exists('description', $data)) $updateData['description'] = $data['description'] ?? null;
        if (array_key_exists('quantity', $data)) $updateData['quantity'] = (int) $data['quantity'];

        if ($imageFile && $imageFile->isValid()) {
            $updateData['image'] = CloudinaryHelper::upload($imageFile, 'products');
        }

        // Cập nhật vào entity
        $updatedEntity = new ProductEntity(
            id: $id,
            name: $updateData['name'] ?? $product->name,
            price: $updateData['price'] ?? $product->price,
            description: $updateData['description'] ?? $product->description,
            quantity: $updateData['quantity'] ?? $product->quantity,
            image: $updateData['image'] ?? $product->image,
            created_at: $product->created_at,
            updated_at: $product->updated_at
        );

        return $this->repo->update($id, $updatedEntity);
    }
}