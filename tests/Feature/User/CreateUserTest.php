<?php

namespace Tests\Feature\User;

use App\Models\User;
use App\Helpers\CloudinaryHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Mockery;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    protected function authAdmin()
    {
        $user = User::factory()->create(['role' => 'admin']);
        return $this->withHeader('Authorization', "Bearer " . JWTAuth::fromUser($user));
    }
    /** @test */
    public function create_user()
    {
        Mockery::mock('alias:' . CloudinaryHelper::class)
            ->shouldReceive('upload')
            ->once()
            ->andReturn('https://fake-cloudinary.com/image.jpg');

        $file = UploadedFile::fake()->create('demo.jpg', 100);
        $payload = [
            'name' => 'Nguyễn Văn A',
            'email' => 'nguyenvana@gmail.com',
            'phone' => '0123456789',
            'address' => 'Thái Bình',
            'image' => $file,
            'password' => bcrypt('123456789'),
            'role' => 'product_manager',
            'status' => 'active'
        ];

        $response = $this->authAdmin()->postJson('/api/users/create', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Nguyễn Văn A'])
            ->assertJsonStructure([
                'data',
                'message'
            ]);
    }
}
