<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWT;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_logout_successfully()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', "Bearer {$token}")
                        ->postJson('/api/logout');
        $response->assertStatus(401) 
                 ->assertJsonStructure([
                    'data' => [
                        'code',
                        'message'
                    ],
                ]);
    }
}