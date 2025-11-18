<?php
// tests/Feature/Auth/MeTest.php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWT;

class MeTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_authenticated_user()
    {
        $user = User::factory()->create(['name' => 'Test User', 'email' => 'me@example.com']);
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->getJson('/api/me');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' =>[
                        'code',
                        'user'
                     ]
                 ]);
    }
}