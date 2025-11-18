<?php

namespace Tests\Feature\Product;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ListProductTest extends TestCase
{
    use RefreshDatabase;

    protected function authAdmin()
    {
        $user = User::factory()->create(['role' => 'admin']);
        return $this->withHeader('Authorization', "Bearer " . JWTAuth::fromUser($user));
    }

    /** @test */
    public function List_product()
    {
        Product::create([
            'name' => 'BimBim Oshi Tôm Cay Xé Lưỡi',
            'price' => 5000,
            'description' => 'Snack Tôm cay',
            'quantity' => 50,
            'image' => 'https://example.com/image.jpg',
        ]);


        $response = $this->authAdmin()->getJson('/api/products/list');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
                'pagination'
            ]);
    }

    /** @test */
    public function search_name_product()
    {
        Product::create([
            'name' => 'BimBim Oshi Tôm Cay Xé Lưỡi',
            'price' => 5000,
            'description' => 'Snack Tôm cay',
            'quantity' => 50,
            'image' => 'https://example.com/image.jpg',
        ]);

        $response = $this->authAdmin()->getJson('/api/products/list?search=BimBim Oshi');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'BimBim Oshi Tôm Cay Xé Lưỡi']);
    }
}
