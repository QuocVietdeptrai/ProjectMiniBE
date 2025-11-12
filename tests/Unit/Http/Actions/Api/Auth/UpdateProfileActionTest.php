<?php

namespace Tests\Unit\Http\Actions\Api\Auth;

use App\Domain\Auth\Domain\Entity\UserEntity;
use App\Domain\Auth\UseCase\UpdateProfileUseCase;
use App\Http\Actions\Api\Auth\UpdateProfileAction;
use App\Http\Responders\Api\Auth\UserResponder;
use App\Http\Resources\Api\Auth\UserResource;
use App\Http\Requests\Auth\UpdateProfileRequest;
use Illuminate\Http\UploadedFile;
use Mockery;
use Tests\TestCase;

class UpdateProfileActionTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // Trả về user resource sau khi cập nhật hồ sơ
    /** @test */
    public function UpdateProfileSuccess()
    {
        $requestData = [
            'name' => 'Nguyen Quoc Viet',
            'phone' => '0391234567',
            'address' => 'Mậu Lương, Hà Đông, Hà Nội'
        ];

        // Fake file 100KB, không cần GD
        $avatar = UploadedFile::fake()->create('avatar.jpg', 100);

        $request = UpdateProfileRequest::create(
            '/api/update-profile',
            'POST',
            $requestData,
            [], // cookies
            ['avatar' => $avatar] // files
        );

        // Giả lập UserEntity trả về
        $userEntity = new UserEntity(
            id: 1,
            name: $requestData['name'],
            email: 'nguyenquocviet2004tb1@gmail.com',
            role: 'admin',
            phone: $requestData['phone'],
            address: $requestData['address'],
            image: 'https://res.cloudinary.com/demo/image/upload/avatar.jpg', // URL Cloudinary giả
            created_at: now()->toDateTimeString()
        );

        // Mock UseCase
        $useCase = Mockery::mock(UpdateProfileUseCase::class);
        $useCase->shouldReceive('__invoke')
                ->once()
                ->with($request->only('name', 'phone', 'address'), $request->file('avatar'))
                ->andReturn($userEntity);

        $responder = new UserResponder();

        $action = new UpdateProfileAction($useCase, $responder);
        $response = $action($request);

        // Kiểm tra loại trả về
        $this->assertInstanceOf(UserResource::class, $response);

        $data = $response->resolve();
        $this->assertEquals('success', $data['code']);
        $this->assertEquals($userEntity, $data['user']);
    }
}
