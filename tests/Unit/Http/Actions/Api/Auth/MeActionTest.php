<?php

namespace Tests\Unit\Http\Actions\Api\Auth;

use App\Domain\Auth\Domain\Entity\UserEntity;
use App\Domain\Auth\UseCase\MeUseCase;
use App\Http\Actions\Api\Auth\MeAction;
use App\Http\Responders\Api\Auth\UserResponder;
use App\Http\Resources\Api\Auth\UserResource;
use Mockery;
use Tests\TestCase;

class MeActionTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // Trả về user resource từ MeUseCase
    /** @test */
    public function MeSuccess()
    {
        // Fake user entity
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

        // Mock MeUseCase
        $useCase = Mockery::mock(MeUseCase::class);
        $useCase->shouldReceive('__invoke')
                ->once()
                ->andReturn($userEntity);

        $responder = new UserResponder();

        $action = new MeAction($useCase, $responder);
        $response = $action();

        // Kiểm tra instance
        $this->assertInstanceOf(UserResource::class, $response);

        // Kiểm tra dữ liệu
        $data = $response->resolve();
        $this->assertEquals('success', $data['code']);
        $this->assertEquals($userEntity, $data['user']);
    }
}
