<?php
// tests/Feature/Product/DeleteProductTest.php

namespace Tests\Feature\Product;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class DeleteProductTest extends TestCase
{
    use RefreshDatabase;

    protected function authAdmin()
    {
        $user = User::factory()->create(['role' => 'product_manager']);
        return $this->withHeader('Authorization', "Bearer " . JWTAuth::fromUser($user));
    }
    /** @test */
    public function delete_product()
    {
        $product = Product::create([
            'id' => 2,
            'name' => 'BimBim Oshi Tôm Cay Xé Lưỡi',
            'price' => 5000,
            'description' => 'Snack Tôm cay',
            'quantity' => 50,
            'image' => 'https://example.com/image.jpg',
        ]);
        $response = $this->authAdmin()->deleteJson("/api/products/delete/{$product->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message'
            ]);
    }
}
