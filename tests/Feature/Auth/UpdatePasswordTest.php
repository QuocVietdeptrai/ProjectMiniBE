<?php
// tests/Feature/Auth/UpdatePasswordTest.php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWT;

class UpdatePasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_password_successfully()
    {
        $user = User::factory()->create(['password' => Hash::make('oldpass123')]);
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->postJson('/api/update_password', [
                             'current_password' => 'oldpass123',
                             'password' => 'newpass123',
                             'password_confirmation' => 'newpass123'
                         ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'data' => [
                        'code',
                        'message'
                    ],
                ]);

        $this->assertTrue(Hash::check('newpass123', $user->fresh()->password));
    }
}