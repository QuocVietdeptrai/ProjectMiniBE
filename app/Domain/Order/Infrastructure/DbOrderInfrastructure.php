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
    //Đếm tổng số đơn hàng
    public function count(): int
    {
        return Order::count();
    }

    //Tìm đơn hàng theo ID
    public function findById(int $id): ?OrderEntity
    {
        $order = Order::with(['items.product', 'student'])->find($id);
        return $order ? $this->toEntity($order) : null;
    }

    //Phân trang danh sách đơn hàng
    public function paginate(?string $search, int $perPage = 4): LengthAwarePaginator
    {
        $query = Order::query();

        if ($search) {
            $query->where('customer_name', 'like', "%{$search}%");
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    //Tạo đơn hàng mới
    public function create(array $data): OrderEntity
    {
        $order = Order::create($data);
        return $this->toEntity($order->load('items.product', 'student'));
    }

    //Cập nhật đơn hàng
    public function update(int $id, array $data): OrderEntity
    {
        $order = Order::findOrFail($id);
        $order->update($data);
        return $this->toEntity($order->load('items.product', 'student'));
    }

    //Xóa đơn hàng
    public function delete(int $id): bool
    {
        return Order::destroy($id) > 0;
    }

    //Chuyển đổi model Order sang entity OrderEntity
    private function toEntity(Order $order): OrderEntity
    {
        $items = $order->items->map(fn($item) => new OrderItemEntity(
            $item->id,
            $item->order_id,
            $item->product_id,
            $item->quantity,
            $item->price,
            $item->subtotal,
            $item->created_at?->toDateTimeString(),
            $item->updated_at?->toDateTimeString()
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


