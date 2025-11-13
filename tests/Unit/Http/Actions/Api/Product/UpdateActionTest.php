<?php

namespace Tests\Unit\Http\Actions\Api\Product;

use App\Domain\Product\Domain\Entity\ProductEntity;
use App\Domain\Product\UseCase\UpdateProductUseCase;
use App\Http\Actions\Api\Product\UpdateAction;
use App\Http\Responders\Api\Product\UpdateProductResponder;
use App\Http\Requests\Product\UpdateProductRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;

class UpdateActionTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function update_success_returns_updated_product()
    {
        $product = new ProductEntity(
            id: 1,
            name: 'Bimbim Poca',
            price: 5000,
            description: 'Ngon giòn rụm',
            quantity: 10,
            image: 'imagebimbim.jpg',
            created_at: now(),
            updated_at: now()
        );

        $useCaseMock = Mockery::mock(UpdateProductUseCase::class);
        $useCaseMock->shouldReceive('__invoke')
            ->once()
            ->andReturn($product);

        $responder = new UpdateProductResponder();

        $request = Mockery::mock(UpdateProductRequest::class);
        $request->shouldReceive('only')
            ->once()
            ->with(['name','price','description','quantity'])
            ->andReturn([
                'name' => 'Bimbim Poca Updated',
                'price' => 6000,
                'description' => 'Mới ngon giòn rụm hơn',
                'quantity' => 15
            ]);
        $request->shouldReceive('file')->once()->with('image')->andReturn(UploadedFile::fake()->create('bimbim.jpg', 100));

        $action = new UpdateAction($useCaseMock, $responder);

        $response = $action($request, 1);

        $this->assertEquals(200, $response->status());
        $data = $response->getData(true);
        $this->assertEquals('Cập nhật thành công', $data['message']);
        $this->assertEquals('Bimbim Poca', $data['data']['name']);
        $this->assertEquals(5000, $data['data']['price']);
        $this->assertEquals('Ngon giòn rụm', $data['data']['description']);
        $this->assertEquals(10, $data['data']['quantity']);
 
    }

    /** @test */
    public function update_returns_404_if_product_not_found()
    {
        $useCaseMock = Mockery::mock(UpdateProductUseCase::class);
        $useCaseMock->shouldReceive('__invoke')
            ->once()
            ->andReturn(null);

        $responder = new UpdateProductResponder();

        $request = Mockery::mock(UpdateProductRequest::class);
        $request->shouldReceive('only')->andReturn([]);
        $request->shouldReceive('file')->andReturnNull();

        $action = new UpdateAction($useCaseMock, $responder);

        $response = $action($request, 999);

        $this->assertEquals(404, $response->status());
        $data = $response->getData(true);
        $this->assertEquals('Sản phẩm không tồn tại', $data['message']);
    }

    /** @test */
    public function update_returns_500_on_exception()
    {
        Log::shouldReceive('info')->once();

        $useCaseMock = Mockery::mock(UpdateProductUseCase::class);
        $useCaseMock->shouldReceive('__invoke')
            ->andThrow(new \Exception('Lỗi giả lập'));

        $responder = new UpdateProductResponder();

        $request = Mockery::mock(UpdateProductRequest::class);
        $request->shouldReceive('only')->andReturn([]);
        $request->shouldReceive('file')->andReturnNull();

        $action = new UpdateAction($useCaseMock, $responder);

        $response = $action($request, 1);

        $this->assertEquals(500, $response->status());
        $data = $response->getData(true);
        $this->assertEquals('Đã xảy ra lỗi khi cập nhật sản phẩm', $data['message']);
    }
}
