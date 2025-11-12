<?php

namespace Tests\Unit\Http\Actions\Api\Auth;

use App\Domain\Auth\Domain\Entity\AuthEntity;
use App\Domain\Auth\UseCase\RegisterUseCase;
use App\Http\Actions\Api\Auth\RegisterAction;
use App\Http\Responders\Api\Auth\MessageResponder;
use App\Http\Resources\Api\Auth\MessageResource;
use App\Http\Requests\Auth\RegisterRequest;
use Mockery;
use Tests\TestCase;

class RegisterActionTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    // Trả về thông báo thành công sau khi đăng ký người dùng
    public function RegisterSuccess()
    {
        $requestData = [
            'name' => 'Nguyen Quoc Viet',
            'email' => 'nguyenquocviet@example.com',
            'password' => 'password123'
        ];

        $request = RegisterRequest::create('/api/register', 'POST', $requestData);

        $authEntity = new AuthEntity(
            token: '',
            user: null,
            message: 'User registered successfully'
        );

        $useCase = Mockery::mock(RegisterUseCase::class);
        $useCase->shouldReceive('__invoke')->once()->with($request)->andReturn($authEntity);

        $responder = new MessageResponder();

        $action = new RegisterAction($useCase, $responder);
        $response = $action($request);

        $this->assertInstanceOf(MessageResource::class, $response);
        $data = $response->resolve();
        $this->assertEquals('success', $data['code']);
        $this->assertEquals('User registered successfully', $data['message']);
    }
}
