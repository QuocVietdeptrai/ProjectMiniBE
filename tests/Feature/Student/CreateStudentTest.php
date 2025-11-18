<?php

namespace Tests\Feature\Product;

use App\Models\User;
use App\Helpers\CloudinaryHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Mockery;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateStudentTest extends TestCase
{
    use RefreshDatabase;

    protected function authAdmin()
    {
        $user = User::factory()->create(['role' => 'admin']);
        return $this->withHeader('Authorization', "Bearer " . JWTAuth::fromUser($user));
    }
    /** @test */
    public function create_student()
    {
        Mockery::mock('alias:' . CloudinaryHelper::class)
            ->shouldReceive('upload')
            ->once()
            ->andReturn('https://fake-cloudinary.com/image.jpg');

        $file = UploadedFile::fake()->create('demo.jpg', 100);
        $payload = [
            'full_name' => 'Nguyễn Văn A',
            'dob' => '06/12/2004',
            'gender' => 'Nam',
            'email' => 'nguyenvana@gmail.com',
            'phone' => '0123456789',
            'class' => 'TT25',
            'avatar' => $file
        ];

        $response = $this->authAdmin()->postJson('/api/students/create', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment(['full_name' => 'Nguyễn Văn A'])
            ->assertJsonStructure([
                'data',
                'message'
            ]);
    }
}
