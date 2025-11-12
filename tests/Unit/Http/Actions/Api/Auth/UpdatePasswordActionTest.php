<?php

namespace Tests\Unit\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\UpdatePasswordUseCase;
use App\Http\Actions\Api\Auth\UpdatePasswordAction;
use App\Http\Responders\Api\Auth\MessageResponder;
use App\Http\Resources\Api\Auth\MessageResource;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use Mockery;
use Tests\TestCase;

class UpdatePasswordActionTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // Trả về thông báo thành công sau khi cập nhật mật khẩu
    /** @test */
    public function UpdatePasswordSuccess()
    {
        $requestData = [
            'password' => 'newpassword123'
        ];

        $request = UpdatePasswordRequest::create('/api/update-password', 'POST', $requestData);

        // Fake result từ use case
        $result = [
            'code' => 'success',
            'message' => 'Password has been updated successfully'
        ];

        $useCase = Mockery::mock(UpdatePasswordUseCase::class);
        $useCase->shouldReceive('__invoke')
                ->once()
                ->with($requestData['password'])
                ->andReturn($result);

        $responder = new MessageResponder();

        $action = new UpdatePasswordAction($useCase, $responder);
        $response = $action($request);

        // Kiểm tra instance
        $this->assertInstanceOf(MessageResource::class, $response);

        // Kiểm tra dữ liệu
        $data = $response->resolve();
        $this->assertEquals('success', $data['code']);
        $this->assertEquals('Password has been updated successfully', $data['message']);
    }
}
