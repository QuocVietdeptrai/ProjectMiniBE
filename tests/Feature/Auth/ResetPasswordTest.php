<?php
// tests/Feature/Auth/ResetPasswordTest.php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_successfully()
    {
        // Tạo user mẫu
        $user = User::factory()->create([
            'email' => 'user@example.com'
        ]);

        // Gọi API reset password
        $response = $this->postJson('/api/reset_password', [
            'email' => $user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123'
        ]);

        // Kiểm tra response đúng cấu trúc và nội dung
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         'code',
                         'message'
                     ]
                 ])
                 ->assertJson([
                     'data' => [
                         'code' => 'success',
                         'message' => 'Đặt lại mật khẩu thành công!'
                     ]
                 ]);
    }
}
