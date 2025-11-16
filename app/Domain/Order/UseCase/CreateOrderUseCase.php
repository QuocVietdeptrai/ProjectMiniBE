<?php

namespace App\Domain\Order\UseCase;

use App\Domain\Order\Domain\Entity\OrderEntity;
use App\Domain\Order\Domain\Repository\OrderRepository;
use App\Domain\Order\Domain\Repository\OrderItemRepository;
use App\Models\Student;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateOrderUseCase
{
    public function __construct(
        protected OrderRepository $orderRepo,
        protected OrderItemRepository $itemRepo
    ) {}

    public function execute(array $data): OrderEntity
    {
        $user = JWTAuth::user();

        $student = Student::firstOrCreate(
            ['phone' => $data['phone']],
            ['full_name' => $data['customer_name'], 'class' => $data['class']]
        );

        $orderData = [
            'student_id' => $student->id,
            'user_id' => $user->id,
            'customer_name' => $data['customer_name'],
            'order_date' => $data['order_date'],
            'status' => $data['status'],
            'payment_method' => $data['payment_method'],
            'total' => $data['total'],
        ];

        $order = $this->orderRepo->create($orderData);
        $student->update(['order_id' => $order->id]);

        $this->itemRepo->createMany($order->id, $data['products']);

        return $this->orderRepo->findById($order->id);
    }
}