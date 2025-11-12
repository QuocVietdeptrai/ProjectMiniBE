<?php

namespace Tests\Unit\Http\Actions\Api\Auth;

use App\Domain\Auth\Domain\Entity\AuthEntity;
use App\Domain\Auth\Domain\Entity\UserEntity;
use App\Domain\Auth\UseCase\RefreshTokenUseCase;
use App\Http\Actions\Api\Auth\RefreshAction;
use App\Http\Responders\Api\Auth\TokenResponder;
use App\Http\Resources\Api\Auth\TokenResource;
use Mockery;
use Tests\TestCase;

class RefreshActionTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // Trả về token resource từ RefreshTokenUseCase
    /** @test */
    public function RefreshSuccess()
    {
        $userEntity = new UserEntity(
            id: 1,
            name: 'Nguyen Quoc Viet',
            email: 'nguyenquocviet@gmail.com',
            role: 'user',
            phone: null,
            address: null,
            image: null,
            created_at: now()->toDateTimeString()
        );

        $authEntity = new AuthEntity(
            token: 'new_token_123',
            user: $userEntity,
            message: 'Token refreshed successfully'
        );

        $useCase = Mockery::mock(RefreshTokenUseCase::class);
        $useCase->shouldReceive('__invoke')->once()->andReturn($authEntity);

        $responder = new TokenResponder();

        $action = new RefreshAction($useCase, $responder);
        $response = $action();

        $this->assertInstanceOf(TokenResource::class, $response);

        $data = $response->resolve();
        $this->assertEquals('success', $data['code']);
        $this->assertEquals('new_token_123', $data['access_token']);
        $this->assertEquals('Token refreshed successfully', $data['message']);
    }
}
