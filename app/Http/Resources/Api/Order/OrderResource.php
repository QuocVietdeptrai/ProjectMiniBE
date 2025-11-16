<?php

namespace App\Http\Resources\Api\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'customer_name' => $this->customerName,
            'order_date' => $this->orderDate,
            'status' => $this->status,
            'payment_method' => $this->paymentMethod,
            'total' => $this->total,
            'student' => [
                'full_name' => $this->student->full_name ?? '',
                'class' => $this->student->class ?? '',
                'phone' => $this->student->phone ?? '',
            ],
            'items' => $this->items?->map(fn($item) => [
                'product_id' => $item->productId,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'name' => $item->productName,
                'image' => $item->productImage,
            ]),
        ];
    }
}