<?php
// tests/Feature/Auth/UpdateProfileTest.php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class UpdateProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_profile_successfully()
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'phone' => '123'
        ]);

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->postJson('/api/update_profile', [
                             'name' => 'New Name',
                             'phone' => '0987654321'
                         ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         'code',
                         'user' => [
                             'id',
                             'name',
                             'email',
                             'role',
                             'phone',
                             'address',
                             'image',
                             'created_at',
                             'last_login_at',
                             'password',
                             'status',
                             'otp',
                             'otp_expires_at'
                         ]
                     ]
                 ])
                 ->assertJson([
                     'data' => [
                         'code' => 'success',
                         'user' => [
                             'id' => $user->id,
                             'name' => 'New Name',
                             'phone' => '0987654321'
                         ]
                     ]
                 ]);

        // Kiểm tra database đã cập nhật thông tin
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'phone' => '0987654321'
        ]);
    }
}
