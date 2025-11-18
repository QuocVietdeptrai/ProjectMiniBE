<?php

namespace Tests\Feature\Student;

use App\Models\User;
use App\Models\Student;
use App\Helpers\CloudinaryHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Mockery;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class UpdateStudentTest extends TestCase
{
    use RefreshDatabase;

    protected function authAdmin()
    {
        $user = User::factory()->create(['role' => 'admin']);
        return $this->withHeader('Authorization', "Bearer " . JWTAuth::fromUser($user));
    }

    /** @test */
    public function can_update_student()
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

        // Mock Cloudinary upload
        Mockery::mock('alias:' . CloudinaryHelper::class)
            ->shouldReceive('upload')
            ->once()
            ->andReturn('https://fake-cloudinary.com/image.jpg');

        $file = UploadedFile::fake()->create('demo.jpg', 100);

        $payload = [
            'full_name' => 'Nguyễn Văn B',
            'dob' => '06/12/2004',
            'gender' => 'Nam',
            'email' => 'nguyenvanb@gmail.com',
            'phone' => '0123456789',
            'class' => 'TT25',
            'avatar' => $file,
        ];

        $response = $this->authAdmin()
            ->postJson("/api/students/update/{$student->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'full_name' => 'Nguyễn Văn B'
            ])
            ->assertJsonStructure([
                'data',
                'message'
            ]);
    }
}
