<?php

namespace Tests\Unit\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\ForgotPasswordUseCase;
use App\Http\Actions\Api\Auth\ForgotPasswordAction;
use App\Http\Resources\Api\Auth\MessageResource;
use App\Http\Responders\Api\Auth\MessageResponder;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use Mockery;
use Tests\TestCase;

class ForgotPasswordActionTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // Trả về thông báo thành công sau khi gửi email đặt lại mật khẩu
    /** @test */
    public function ForgotPasswordSuccess()
    {
        $email = 'nguyenquocviet@gmail.com';
        $request = ForgotPasswordRequest::create('/api/forgot-password', 'POST', [
            'email' => $email
        ]);

        $useCase = Mockery::mock(ForgotPasswordUseCase::class);
        $useCase->shouldReceive('__invoke')
                ->once()
                ->with($email)
                ->andReturn([
                    'code' => 'success',
                    'message' => 'Password reset email sent'
                ]);

        $responder = new MessageResponder();

        $action = new ForgotPasswordAction($useCase, $responder);
        $response = $action($request);

        $this->assertInstanceOf(MessageResource::class, $response);
        $this->assertEquals('success', $response->resolve()['code']);
        $this->assertEquals('Password reset email sent', $response->resolve()['message']);
    }

    // Trả về thông báo lỗi khi email không tồn tại
    /** @test */
    public function EmailNotFound()
    {
        $email = 'notfound@example.com';
        $request = ForgotPasswordRequest::create('/api/forgot-password', 'POST', [
            'email' => $email
        ]);

        $useCase = Mockery::mock(ForgotPasswordUseCase::class);
        $useCase->shouldReceive('__invoke')
                ->once()
                ->with($email)
                ->andReturn([
                    'code' => 'error',
                    'message' => 'Email not found'
                ]);

        $responder = new MessageResponder();

        $action = new ForgotPasswordAction($useCase, $responder);
        $response = $action($request);

        $this->assertInstanceOf(MessageResource::class, $response);
        $this->assertEquals('error', $response->resolve()['code']);
        $this->assertEquals('Email not found', $response->resolve()['message']);
    }
}
