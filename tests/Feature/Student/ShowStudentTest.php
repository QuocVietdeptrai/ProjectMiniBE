<?php

namespace Tests\Feature\Student;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ShowStudentTest extends TestCase
{
    use RefreshDatabase;

    protected function authAdmin()
    {
        $user = User::factory()->create(['role' => 'admin']);
        return $this->withHeader('Authorization', "Bearer " . JWTAuth::fromUser($user));
    }

    /** @test */
    public function detail_student()
    {
        $student = Student::create([
            'full_name' => 'Nguyễn Văn A',
            'dob' => '06/12/2004',
            'gender' => 'Nam',
            'email' => 'nguyenvana@gmail.com',
            'phone' => '0123456789',
            'class' => 'TT25',
            'avatar' => 'https://example.com/image.jpg',
        ]);

        $response = $this->authAdmin()->getJson("/api/students/{$student->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['full_name' => 'Nguyễn Văn A']);
    }
}
