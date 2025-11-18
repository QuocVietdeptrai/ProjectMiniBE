<?php
// tests/Feature/Order/CreateOrderTest.php

namespace Tests\Feature\Order;

use App\Models\Product;
use App\Models\User;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function authAdmin()
    {
        $user = User::factory()->create(['role' => 'admin']);
        return $this->withHeader('Authorization', "Bearer " . JWTAuth::fromUser($user));
    }

    /** @test */
    public function can_create_order()
    {
        $user = User::factory()->create();
        $product = Product::create([
            'name' => 'BimBim Oshi Tôm Cay Xé Lưỡi',
            'price' => 5000,
            'description' => 'Snack Tôm cay',
            'quantity' => 50,
            'image' => 'https://example.com/image.jpg',
        ]);

        // payload của order
        $payload = [
            'user_id' => $user->id,
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
                    "quantity" => 2,
                    "price" => $product->price,
                    "subtotal" => 2 * $product->price
                ]
            ]
        ];

        // Tạo student tương ứng
        $student = Student::create([
            'full_name' => $payload['customer_name'],
            'dob' => '2004-12-06',
            'gender' => 'Nam',
            'email' => 'nguyenvana@gmail.com',
            'phone' => $payload['phone'],
            'class' => $payload['class'],
            'avatar' => 'https://example.com/image.jpg',
        ]);

        $payload['student_id'] = $student->id;

        $response = $this->authAdmin()->postJson('/api/orders/create', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment(['customer_name' => 'Nguyễn Quốc Việt'])
                 ->assertJsonFragment(['subtotal' => 10000])
                 ->assertJsonStructure([
                    'data',
                    'message'
                 ]);
    }
}
