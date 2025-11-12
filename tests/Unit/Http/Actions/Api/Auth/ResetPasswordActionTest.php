<?php

namespace Tests\Unit\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\ResetPasswordUseCase;
use App\Http\Actions\Api\Auth\ResetPasswordAction;
use App\Http\Responders\Api\Auth\MessageResponder;
use App\Http\Resources\Api\Auth\MessageResource;
use App\Http\Requests\Auth\ResetPasswordRequest;
use Mockery;
use Tests\TestCase;

class ResetPasswordActionTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // Trả về thông báo thành công sau khi đặt lại mật khẩu
    /** @test */
    public function ResetPasswordSuccess()
    {
        $requestData = [
            'email' => 'nguyenquocviet@gmail.com',
            'password' => 'newpassword123'
        ];

        $request = ResetPasswordRequest::create('/api/reset-password', 'POST', $requestData);

        // Fake result từ use case
        $result = [
            'code' => 'success',
            'message' => 'Password has been reset successfully'
        ];

        $useCase = Mockery::mock(ResetPasswordUseCase::class);
        $useCase->shouldReceive('__invoke')
                ->once()
                ->with($requestData['email'], $requestData['password'])
                ->andReturn($result);

        $responder = new MessageResponder();

        $action = new ResetPasswordAction($useCase, $responder);
        $response = $action($request);

        // Kiểm tra instance
        $this->assertInstanceOf(MessageResource::class, $response);

        // Kiểm tra dữ liệu
        $data = $response->resolve();
        $this->assertEquals('success', $data['code']);
        $this->assertEquals('Password has been reset successfully', $data['message']);
    }
}
