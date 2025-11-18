<?php
// tests/Feature/Auth/RegisterTest.php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_successfully()
    {
        $payload = [
            'name' => 'Nguyễn Văn A',
            'email' => 'nguyenvana@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/register', $payload);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'data' => [
                        'code',
                        'message',
                    ]
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'nguyenvana@example.com',
            'name' => 'Nguyễn Văn A'
        ]);
    }

    public function test_register_fails_with_duplicate_email()
    {
        User::factory()->create(['email' => 'exists@example.com']);

        $response = $this->postJson('/api/register', [
            'name' => 'Test',
            'email' => 'exists@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
                 ->assertJsonStructure([
                    'code',
                    'errors' => [
                        'email'
                    ]
                 ]);
    }
}