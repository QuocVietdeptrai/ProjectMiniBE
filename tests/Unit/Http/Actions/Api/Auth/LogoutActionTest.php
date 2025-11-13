<?php

namespace Tests\Unit\Http\Actions\Api\Auth;

use App\Http\Actions\Api\Auth\LogoutAction;
use App\Domain\Auth\UseCase\LogoutUseCase;
use App\Http\Responders\Api\Auth\MessageResponder;
use App\Http\Resources\Api\Auth\MessageResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Mockery;
use Tests\TestCase;

class LogoutActionTest extends TestCase
{
    /** @test */
    //Đăng xuất thành công
    public function LogoutSuccess()
    {
        // Mock các dependency
        $useCase = Mockery::mock(LogoutUseCase::class);
        $responder = Mockery::mock(MessageResponder::class);
        $messageResource = Mockery::mock(MessageResource::class);

        // UseCase được gọi 1 lần với token
        $useCase->shouldReceive('__invoke')->once()->with('fake_token');

        // MessageResponder trả về MessageResource
        $responder->shouldReceive('__invoke')
            ->once()
            ->andReturn($messageResource);

        // MessageResource có method response() -> JsonResponse
        $messageResource->shouldReceive('response')
            ->once()
            ->andReturn(new JsonResponse([
                'code' => 'success',
                'message' => 'Logout successful',
            ]));

        // Gọi action
        $action = new LogoutAction($useCase, $responder);

        $request = Request::create('/logout', 'POST');
        $request->cookies->set('access_token', 'fake_token');

        $response = $action($request);

        // Kiểm tra
        $this->assertEquals(200, $response->status());
        $this->assertEquals('Logout successful', $response->getData()->message);
    }
    //Đăng xuất thất bạt 
    /** @test */
    public function LogoutFailsWithoutToken(){
        // Mock các dependency
        $useCase = Mockery::mock(LogoutUseCase::class);
        $responder = Mockery::mock(MessageResponder::class);
        $messageResource = Mockery::mock(MessageResource::class);

        // MessageResponder trả về MessageResource
        $responder->shouldReceive('__invoke')
            ->once()
            ->andReturn($messageResource);

        // MessageResource có method response() -> JsonResponse
        $messageResource->shouldReceive('response')
            ->once()
            ->andReturn(new JsonResponse([
                'code' => 'error',
                'message' => 'Token not found',
            ], 401));

        // Gọi action
        $action = new LogoutAction($useCase, $responder);

        $request = Request::create('/logout', 'POST');
        // Không có token trong cookie

        $response = $action($request);

        // Kiểm tra
        $this->assertEquals(401, $response->status());
        $this->assertEquals('Token not found', $response->getData()->message);
    }
}
