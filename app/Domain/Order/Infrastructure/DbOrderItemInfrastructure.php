<?php

namespace App\Domain\Order\Infrastructure;

use App\Domain\Order\Domain\Entity\OrderItemEntity;
use App\Domain\Order\Domain\Repository\OrderItemRepository;
use App\Models\OrderItem;
use Illuminate\Support\Collection;

class DbOrderItemInfrastructure implements OrderItemRepository
{
    //Tạo nhiều sản phẩm cho một đơn hàng
    public function createMany(int $orderId, array $items): Collection
    {
        $created = [];
        foreach ($items as $item) {
            $model = OrderItem::create([
                'order_id' => $orderId,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['subtotal'] ?? ($item['quantity'] * $item['price']),
            ]);
            $created[] = new OrderItemEntity(
                $model->id,
                $model->order_id,
                $model->product_id,
                $model->quantity,
                $model->price,
                $model->subtotal
            );
        }
        return collect($created);
    }

    //Xóa sản phẩm theo orderId
    public function deleteByOrderId(int $orderId): bool
    {
        return OrderItem::where('order_id', $orderId)->delete() > 0;
    }

    //Lấy sản phẩm theo orderId
    public function getByOrderId(int $orderId): Collection
    {
        return OrderItem::where('order_id', $orderId)->get()->map(fn($item) => new OrderItemEntity(
            $item->id,
            $item->order_id,
            $item->product_id,
            $item->quantity,
            $item->price,
            $item->subtotal
        ));
    }
}