<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_reset_link_successfully()
    {
        $user = User::factory()->create(['email' => 'user@example.com']);

        $response = $this->postJson('/api/forgotpassword', [
            'email' => 'user@example.com'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'data' => [
                        'code',
                        'message'
                    ],
                ]);
    }

    public function test_forgot_password_with_nonexistent_email()
    {
        $response = $this->postJson('/api/forgotpassword', [
            'email' => 'notexist@example.com'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'data' => [
                        'code',
                        'message'
                    ],
                ]);
    }
}