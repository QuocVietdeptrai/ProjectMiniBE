<?php

namespace App\Http\Resources\Api\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'customer_name' => $this->student['full_name'] ?? null,
            'order_date' => $this->order_date,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'total' => $this->total,
            'student' => [
                'full_name' => $this->student->full_name ?? '',
                'class' => $this->student->class ?? '',
                'phone' => $this->student->phone ?? '',
            ],
            'items' => $this->items?->map(fn($item) => [
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'subtotal' => $item->subtotal,
            ]),
        ];
    }
}