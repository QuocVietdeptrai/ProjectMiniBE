<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ShowUserTest extends TestCase
{
    use RefreshDatabase;

    protected function authAdmin()
    {
        $user = User::factory()->create(['role' => 'admin']);
        return $this->withHeader('Authorization', "Bearer " . JWTAuth::fromUser($user));
    }

    /** @test */
    public function detail_user()
    {
        $user = User::create([
            'name' => 'Nguyễn Văn A',
            'email' => 'nguyenvana@gmail.com',
            'phone' => '0123456789',
            'address' => 'Thái Bình',
            'image' => 'https://example.com/image.jpg',
            'password' => bcrypt('123456789'),
            'role' => 'product_manager',
            'status' => 'active'
        ]);

        $response = $this->authAdmin()->getJson("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Nguyễn Văn A']);
    }
}
