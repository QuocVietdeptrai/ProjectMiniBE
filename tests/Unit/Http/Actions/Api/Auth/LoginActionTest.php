<?php

namespace Tests\Unit\Http\Actions\Api\Auth;

use App\Domain\Auth\Domain\Entity\AuthEntity;
use App\Domain\Auth\Domain\Entity\UserEntity;
use App\Domain\Auth\UseCase\LoginUseCase;
use App\Http\Actions\Api\Auth\LoginAction;
use App\Http\Responders\Api\Auth\LoginResponder;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Api\Auth\LoginResource;
use Mockery;
use Tests\TestCase;

class LoginActionTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    //Trả về JSON response với cookie và dữ liệu người dùng
    /** @test */
    public function LoginSuccess()
    {
        $request = LoginRequest::create('/api/login', 'POST', [
            'email' => 'nguyenquocviet@gmail.com',
            'password' => 'password123'
        ]);

        $user = new UserEntity(
            id: 1,
            name: 'Nguyen Quoc Viet',
            email: 'nguyenquocviet@gmail.com',
            role: 'admin',
            phone: null,
            address: null,
            image: null,
            created_at: now()->toDateTimeString()
        );

        $authEntity = new AuthEntity(
            token: 'valid_token_123',
            user: $user,
            message: 'Login success'
        );

        $useCase = Mockery::mock(LoginUseCase::class);
        $useCase->shouldReceive('__invoke')->once()->with($request)->andReturn($authEntity);

        $responder = new LoginResponder();

        $action = new LoginAction($useCase, $responder);
        $response = $action($request);

        $this->assertJson($response->getContent());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals('valid_token_123', $data['access_token']);
        $this->assertEquals($user->id, $data['user']['id']);

        $cookies = $response->headers->getCookies();
        $this->assertNotEmpty($cookies);
        $cookieNames = array_map(fn($c) => $c->getName(), $cookies);
        $this->assertContains('access_token', $cookieNames);
    }
}
