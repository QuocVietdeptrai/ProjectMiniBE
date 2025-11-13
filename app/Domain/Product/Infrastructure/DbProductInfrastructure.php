<?php

namespace App\Domain\Product\Infrastructure;
use App\Domain\Product\Domain\Repository\ProductRepository;
use App\Models\Product;
use App\Domain\Product\Domain\Entity\ProductEntity;
use Illuminate\Pagination\LengthAwarePaginator;

class DbProductInfrastructure implements ProductRepository
{
    public function count(): int
    {
        return Product::count();
    }
    public function paginate(?string $search, int $perPage = 4): LengthAwarePaginator
    {
        $query = Product::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function all(?string $search): array
    {
        $query = Product::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        return $query->orderBy('created_at', 'desc')->get()->map(fn($m) => $this->toEntity($m))->toArray();
    }

    public function create(ProductEntity $entity): ProductEntity
    {
        $model = Product::create([
            'name' => $entity->name,
            'price' => $entity->price,
            'description' => $entity->description,
            'quantity' => $entity->quantity,
            'image' => $entity->image,
        ]);

        return $this->toEntity($model);
    }

    public function find(int $id): ?ProductEntity
    {
        $model = Product::find($id);
        return $model ? $this->toEntity($model) : null;
    }

    public function update(int $id, ProductEntity $entity): ?ProductEntity
    {
        $model = Product::find($id);
        if (!$model) return null;

        $model->update([
            'name' => $entity->name,
            'price' => $entity->price,
            'description' => $entity->description,
            'quantity' => $entity->quantity,
            'image' => $entity->image,
        ]);

        return $this->toEntity($model);
    }

    public function delete(int $id): bool
    {
        $model = Product::find($id);
        return $model ? $model->delete() : false;
    }

    private function toEntity(Product $model): ProductEntity
    {
        return new ProductEntity(
            id: $model->id,
            name: $model->name,
            price: (float) $model->price,
            description: $model->description,
            quantity: (int) $model->quantity,
            image: $model->image,
            created_at: $model->created_at?->format('Y-m-d H:i:s'),
            updated_at: $model->updated_at?->format('Y-m-d H:i:s')
        );
    }
}