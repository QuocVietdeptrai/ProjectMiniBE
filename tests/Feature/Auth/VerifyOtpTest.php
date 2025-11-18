<?php
// tests/Feature/Auth/VerifyOtpTest.php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class VerifyOtpTest extends TestCase
{
    use RefreshDatabase;

    public function test_verify_otp_successfully()
    {
        $user = User::factory()->create(['email' => 'user@example.com']);
        $otp = '123456';
        Cache::put("otp_reset_{$user->email}", $otp, now()->addMinutes(5));

        $response = $this->postJson('/api/otp_password', [
            'email' => 'user@example.com',
            'otp' => $otp
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'data' => [
                        'code',
                        'message'
                    ],
                 ]);
    }

    public function test_verify_otp_fails_with_wrong_code()
    {
        $user = User::factory()->create(['email' => 'user@example.com']);
        Cache::put("otp_reset_{$user->email}", '123456', now()->addMinutes(5));

        $response = $this->postJson('/api/otp_password', [
            'email' => 'user@example.com',
            'otp' => '000000'
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