<?php

namespace Tests\Feature\Order;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class UpdateOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function authAdmin()
    {
        $user = User::factory()->create(['role' => 'admin']);
        return $this->withHeader('Authorization', "Bearer " . JWTAuth::fromUser($user));
    }

    /** @test */
    public function can_update_order()
    {
        $user = User::factory()->create();

        // 1. Student
        $student = Student::create([
            'full_name' => 'Nguyễn Quốc Việt',
            'dob' => '2004-12-06',
            'gender' => 'Nam',
            'email' => 'nguyenviet@gmail.com',
            'phone' => '0123456789',
            'class' => 'TT25',
            'avatar' => 'https://example.com/image.jpg',
        ]);

        // 2. Product
        $product = Product::create([
            'name' => 'BimBim Oshi Tôm Cay Xé Lưỡi',
            'price' => 5000,
            'description' => 'Snack Tôm cay',
            'quantity' => 50,
            'image' => 'https://example.com/image.jpg',
        ]);

        // 3. Order ban đầu
        $order = Order::create([
            'user_id' => $user->id,
            'student_id' => $student->id,
            'customer_name' => 'Nguyễn Quốc Việt',
            'phone' => '0123456789',
            'class' => 'TT25',
            'order_date' => '2025-11-10',
            'status' => 'pending',
            'payment_method' => 'cash',
            'total' => 1500000,
            'products' => [
                [
                    "id" => $product->id,
                    "quantity" => 1,
                    "price" => $product->price,
                    "subtotal" => $product->price
                ]
            ]
        ]);

        // 4. Payload update
        $payload = [
            'user_id' => $user->id,
            'student_id' => $student->id,
            'customer_name' => 'Nguyễn Quốc Tuấn',
            'phone' => '0123456789',
            'class' => 'TT25',
            'order_date' => '2025-11-10',
            'status' => 'pending',
            'payment_method' => 'cash',
            'total' => 2000000,
            'products' => [
                [
                    "id" => $product->id,
                    "quantity" => 2,
                    "price" => $product->price,
                    "subtotal" => 2 * $product->price
                ]
            ]
        ];

        $response = $this->authAdmin()
            ->postJson("/api/orders/update/{$order->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'customer_name' => 'Nguyễn Quốc Tuấn'
            ])
            ->assertJsonStructure([
                'data',
                'message'
            ]);
    }
}
