<?php

namespace Tests\Unit\Http\Actions\Api\Product;

use App\Domain\Product\UseCase\DeleteProductUseCase;
use App\Http\Actions\Api\Product\DestroyAction;
use App\Http\Responders\Api\Product\DestroyProductResponder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Mockery;
use Tests\TestCase;

class DestroyActionTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    // Trả về phản hồi thành công khi xóa sản phẩm
    public function DestroySuccess()
    {
        $id = 1;
        // Mock UseCase thành công
        $useCase = Mockery::mock(DeleteProductUseCase::class);
        $useCase->shouldReceive('__invoke')
                ->once()
                ->with($id)
                ->andReturnTrue();

        $responder = new DestroyProductResponder();
        $action = new DestroyAction($useCase, $responder);

        $response = $action($id);

        // Kiểm tra kiểu trả về
        $this->assertInstanceOf(JsonResponse::class, $response);

        $data = $response->getData(true);
        $this->assertEquals('success', $data['status']);
        $this->assertEquals('Xóa sản phẩm thành công', $data['message']);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    // Trả về 404 khi sản phẩm không tồn tại
    public function ProductNotFound()
    {
        $id = 1;

        // Mock UseCase ném ngoại lệ
        $useCase = Mockery::mock(DeleteProductUseCase::class);
        $useCase->shouldReceive('__invoke')
                ->once()
                ->with($id)
                ->andThrow(new ModelNotFoundException());

        $responder = new DestroyProductResponder();
        $action = new DestroyAction($useCase, $responder);

        $response = $action($id);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $data = $response->getData(true);
        $this->assertEquals('Sản phẩm không tồn tại', $data['message']);
        $this->assertEquals(404, $response->getStatusCode());
    }
}
