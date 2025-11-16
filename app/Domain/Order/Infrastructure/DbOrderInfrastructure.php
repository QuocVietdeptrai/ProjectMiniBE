<?php

namespace App\Domain\Order\Infrastructure;

use App\Domain\Order\Domain\Entity\OrderEntity;
use App\Domain\Order\Domain\Entity\OrderItemEntity;
use App\Domain\Order\Domain\Repository\OrderRepository;
use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class DbOrderInfrastructure implements OrderRepository
{
    public function count(): int
    {
        return Order::count();
    }

    public function findById(int $id): ?OrderEntity
    {
        $order = Order::with(['items.product', 'student'])->find($id);
        return $order ? $this->toEntity($order) : null;
    }

    public function paginate(array $filters, int $perPage = 5): LengthAwarePaginator
    {
        $query = Order::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('total', 'like', "%{$search}%")
                  ->orWhere('payment_method', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhereDate('created_at', $search);
            });
        }

        $query->orderBy('created_at', 'desc');

        return $query->paginate($perPage);
    }

    public function create(array $data): OrderEntity
    {
        $order = Order::create($data);
        return $this->toEntity($order->load('items.product', 'student'));
    }

    public function update(int $id, array $data): OrderEntity
    {
        $order = Order::findOrFail($id);
        $order->update($data);
        return $this->toEntity($order->load('items.product', 'student'));
    }

    public function delete(int $id): bool
    {
        return Order::destroy($id) > 0;
    }

    private function toEntity(Order $order): OrderEntity
    {
        $items = $order->items->map(fn($item) => new OrderItemEntity(
            $item->id,
            $item->order_id,
            $item->product_id,
            $item->quantity,
            $item->price,
            $item->product->name ?? null,
            $item->product->image ?? null
        ));

        return new OrderEntity(
            $order->id,
            $order->student_id,
            $order->user_id,
            $order->customer_name,
            $order->order_date,
            $order->status,
            $order->payment_method,
            $order->total,
            $items,
            $order->student,
            $order->created_at?->toDateTimeString(),
            $order->updated_at?->toDateTimeString()
        );
    }
}


