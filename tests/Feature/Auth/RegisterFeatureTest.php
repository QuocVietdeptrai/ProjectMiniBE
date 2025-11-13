<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class RegisterFeatureTest extends TestCase
{
    use RefreshDatabase;

    private array $userData = [
        'name' => 'Test User',
        'email' => 'testuser@example.com',
        'password' => 'password123',
    ];

    /**
     * Test đăng ký user thành công
     */
    public function test_register_user_success()
    {
        $response = $this->postJson('/api/register', $this->userData);

        // Status code thực tế
        $response->assertStatus(200);

        // JSON structure theo MessageResource
        $response->assertJsonStructure([
            'data' => [
                'code',
                'message'
            ]
        ]);

        // Kiểm tra user đã tồn tại trong database
        $this->assertDatabaseHas('users', [
            'name' => $this->userData['name'],
            'email' => $this->userData['email'],
        ]);
    }

    /**
     * Test đăng ký thất bại với dữ liệu không hợp lệ
     */
    public function test_register_user_fails_with_invalid_data()
    {
        $invalidData = [
            'name' => '',
            'email' => 'not-an-email',
            'password' => '123',
        ];

        $response = $this->postJson('/api/register', $invalidData);

        // Status code khi validation fail
        $response->assertStatus(422);

        // JSON structure trả về
        $response->assertJsonStructure([
            'code',
            'errors'
        ]);
    }

    /**
     * Test đăng ký thất bại nếu email hoặc phone đã tồn tại
     */
    public function test_register_user_fails_if_email_or_phone_exists()
    {
        // Tạo sẵn user trong database
        User::factory()->create([
            'email' => $this->userData['email'],
        ]);

        $response = $this->postJson('/api/register', $this->userData);

        // Status code khi duplicate
        $response->assertStatus(422);

        $response->assertJsonStructure([
            'code',
            'errors'
        ]);
    }
}
