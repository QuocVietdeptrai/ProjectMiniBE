<?php

namespace Tests\Feature\Product;

use App\Models\User;
use App\Models\Product;
use App\Helpers\CloudinaryHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Mockery;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class UpdateProductTest extends TestCase
{
    use RefreshDatabase;

    protected function authAdmin()
    {
        $user = User::factory()->create(['role' => 'admin']);
        return $this->withHeader('Authorization', "Bearer " . JWTAuth::fromUser($user));
    }

    /** @test */
    public function can_update_product()
    {
        // Tạo product để update
        $product = Product::create([
            'name' => 'BimBim Oshi Tôm Cay Xé Lưỡi',
            'price' => 5000,
            'description' => 'Snack Tôm cay',
            'quantity' => 50,
            'image' => 'https://example.com/image.jpg',
        ]);

        // Mock Cloudinary upload
        Mockery::mock('alias:' . CloudinaryHelper::class)
            ->shouldReceive('upload')
            ->once()
            ->andReturn('https://fake-cloudinary.com/image.jpg');

        $file = UploadedFile::fake()->create('demo.jpg', 100);

        $payload = [
            'name' => 'Red Bull Sugar Free',
            'description' => 'Nước tăng lực',
            'price' => 15000,
            'quantity' => 100,
            'image' => $file
        ];

        $response = $this->authAdmin()
            ->postJson("/api/products/update/{$product->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Red Bull Sugar Free'
            ])
            ->assertJsonStructure([
                'data',
                'message'
            ]);
    }
}
