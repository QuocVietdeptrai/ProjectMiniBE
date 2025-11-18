<?php

namespace Tests\Feature\Order;

use App\Models\Order;
use App\Models\Product;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class DeleteOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function authAdmin()
    {
        $user = User::factory()->create(['role' => 'admin']);
        return $this->withHeader('Authorization', "Bearer " . JWTAuth::fromUser($user));
    }

    /** @test */
    public function delete_order()
    {
        $user = User::factory()->create();

        $product = Product::create([
            'name' => 'BimBim Oshi Tôm Cay Xé Lưỡi',
            'price' => 5000,
            'description' => 'Snack Tôm cay',
            'quantity' => 50,
            'image' => 'https://example.com/image.jpg',
        ]);

        $order = Order::create([
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
        ]);

        $student = Student::create([
            'full_name' => $order->customer_name,
            'dob' => '2004-12-06',
            'gender' => 'Nam',
            'email' => 'nguyenvana@gmail.com',
            'phone' => $order->phone,
            'class' => $order->class,
            'avatar' => 'https://example.com/image.jpg',
        ]);

        $order->update(['student_id' => $student->id]);


        $response = $this->authAdmin()->deleteJson("/api/orders/delete/{$order->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message'
            ]);
    }
}
