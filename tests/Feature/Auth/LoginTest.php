<?php
// tests/Feature/Auth/LoginTest.php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('password123')
        ]);
    }

    public function test_login_successfully()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'user@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'code',
                    'access_token',
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'role',
                        'phone',
                        'address',
                        'image',
                        'created_at',
                        'last_login_at'
                    ]
                ]);
    }

    public function test_login_fails_with_wrong_password()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'user@example.com',
            'password' => 'password1234'
        ]);

        $response->assertStatus(401)
                 ->assertJsonStructure([
                    'status',
                    'message'
                ]);
    }
}