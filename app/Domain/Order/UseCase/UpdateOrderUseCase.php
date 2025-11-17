<?php

namespace App\Domain\Order\UseCase;

use App\Domain\Order\Domain\Entity\OrderEntity;

use App\Domain\Order\Domain\Repository\OrderItemRepository;
use App\Domain\Order\Domain\Repository\OrderRepository;
use App\Models\Product;
use App\Models\Student;
use App\Domain\Order\Domain\Entity\OrderItemEntity;
use App\Domain\Order\Exception\OrderNotFoundException;
use App\Domain\Order\Exception\StockException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateOrderUseCase
{
    public function __construct(
        protected OrderRepository $orderRepo,
        protected OrderItemRepository $itemRepo
    ) {}

    public function execute(int $id, array $data): OrderEntity
    {
        $order = $this->orderRepo->findById($id);
        if (!$order) {
            throw new OrderNotFoundException();
        }
        $oldStatus = $order->status;
        $student = Student::updateOrCreate(
            ['phone' => $data['phone']],
            ['full_name' => $data['customer_name'], 'class' => $data['class']]
        );

        $updateData = [
            'customer_name' => $data['customer_name'],
            'order_date' => $data['order_date'],
            'status' => $data['status'],
            'payment_method' => $data['payment_method'],
            'total' => $data['total'],
            'student_id' => $student->id,
        ];

        $this->itemRepo->deleteByOrderId($id);
        $this->itemRepo->createMany($id, $data['products']);

        // Trừ kho nếu chuyển sang completed
        if ($data['status'] === 'completed' && $oldStatus !== 'completed') {
            foreach ($data['products'] as $p) {
                $product = Product::find($p['id']);
                if (!$product || $product->quantity < $p['quantity']) {
                    throw new StockException();
                }
                $product->decrement('quantity', $p['quantity']);
            }
        }

        // Hoàn kho nếu hủy đơn đã hoàn tất
        if ($oldStatus === 'completed' && $data['status'] !== 'completed') {
            /** @var OrderItemEntity[] $oldItems */
            $oldItems = $this->itemRepo->getByOrderId($id);
            foreach ($oldItems as $item) {
                /** @var OrderItemEntity $item */
                $product = Product::find((int)$item->product_id);
                if ($product) {
                    $product->increment('quantity', (int)$item->quantity);
                }
            }
        }

        return $this->orderRepo->update($id, $updateData);
    }
}