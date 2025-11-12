<?php

namespace Tests\Unit\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\CheckAuthUseCase;
use App\Http\Actions\Api\Auth\CheckAuthAction;
use App\Http\Responders\Api\Auth\UserResponder;
use App\Http\Resources\Api\Auth\MessageResource;
use App\Http\Resources\Api\Auth\UserResource;
use App\Domain\Auth\Domain\Entity\UserEntity;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class CheckAuthActionTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // Trả về user resource nếu token tồn tại
    /** @test */
    public function CheckAuthSuccess()
    {
        $token = 'valid_token';
        $userEntity = new UserEntity(
            id: 1,
            name: 'Nguyen Quoc Viet',
            email: 'nguyenquocviet@gmail.com',
            role: 'admin',
            phone: null,
            address: null,
            image: null,
            created_at: now()->toDateTimeString()
        );


        //Gỉa lập request với cookie chứa token
        $request = Request::create('/api/check-auth', 'GET');
        $request->cookies->set('access_token', $token);

        // Trả về $userEntity
        $useCase = Mockery::mock(CheckAuthUseCase::class);
        $useCase->shouldReceive('__invoke')->once()->with($token)->andReturn($userEntity);

        // Responder sẽ trả về UserResource được tạo từ entity
        $responder = Mockery::mock(UserResponder::class);
        $responder->shouldReceive('__invoke')->once()->with($userEntity)->andReturnUsing(function($user) {
            return new UserResource($user);
        });

        $action = new CheckAuthAction($useCase, $responder);
        $response = $action($request);

        $this->assertInstanceOf(UserResource::class, $response);//$response phải là UserResource
        $this->assertEquals('success', $response->resolve()['code']);//Trả về code là success
        $this->assertEquals($userEntity, $response->resolve()['user']);//Phần user trong resource phải đúng là $userEntity trả về từ use case
    }

    // Trả về message resource khi không có token
    /** @test */
    public function CheckAuthToken()
    {
        $request = Request::create('/api/check-auth', 'GET');

        $useCase = Mockery::mock(CheckAuthUseCase::class);
        $useCase->shouldNotReceive('__invoke');

        $responder = Mockery::mock(UserResponder::class);
        $responder->shouldNotReceive('__invoke');

        $action = new CheckAuthAction($useCase, $responder);
        $response = $action($request);

        $this->assertInstanceOf(MessageResource::class, $response);
        $this->assertEquals('error', $response->resolve()['code']);
        $this->assertEquals('Token not found', $response->resolve()['message']);
    }
}
