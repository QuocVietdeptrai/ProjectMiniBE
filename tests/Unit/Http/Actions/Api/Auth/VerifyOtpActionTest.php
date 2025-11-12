<?php

namespace Tests\Unit\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\VerifyOtpUseCase;
use App\Http\Actions\Api\Auth\VerifyOtpAction;
use App\Http\Responders\Api\Auth\MessageResponder;
use App\Http\Resources\Api\Auth\MessageResource;
use App\Http\Requests\Auth\OTPPasswordRequest;
use Mockery;
use Tests\TestCase;

class VerifyOtpActionTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    // Trả về thông báo thành công khi OTP được xác minh
    public function OTPSuccess()
    {
        $requestData = [
            'otp' => '123456'
        ];

        $request = OTPPasswordRequest::create('/api/verify-otp', 'POST', $requestData);

        // Fake result từ use case
        $result = [
            'code' => 'success',
            'message' => 'OTP verified successfully'
        ];

        $useCase = Mockery::mock(VerifyOtpUseCase::class);
        $useCase->shouldReceive('__invoke')
                ->once()
                ->with($requestData['otp'])
                ->andReturn($result);

        $responder = new MessageResponder();

        $action = new VerifyOtpAction($useCase, $responder);
        $response = $action($request);

        $this->assertInstanceOf(MessageResource::class, $response);

        $data = $response->resolve();
        $this->assertEquals('success', $data['code']);
        $this->assertEquals('OTP verified successfully', $data['message']);
    }
}
