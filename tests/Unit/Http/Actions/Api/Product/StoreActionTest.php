<?php

namespace Tests\Unit\Http\Actions\Api\Product;

use App\Domain\Product\Domain\Entity\ProductEntity;
use App\Domain\Product\UseCase\CreateProductUseCase;
use App\Http\Actions\Api\Product\StoreAction;
use App\Http\Responders\Api\Product\StoreProductResponder;
use App\Http\Requests\Product\StoreProductRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;

class StoreActionTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function store_success_returns_created_product()
    {
        // 1. Mock ProductEntity
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
        

        // 2. Mock UseCase
        $useCaseMock = Mockery::mock(CreateProductUseCase::class);
        $useCaseMock->shouldReceive('__invoke')
            ->once()
            ->andReturn($product);

        // 3. Responder thật
        $responder = new StoreProductResponder();

        // 4. Mock request
        $request = Mockery::mock(StoreProductRequest::class);
        $request->shouldReceive('only')
            ->once()
            ->with(['name', 'price', 'description', 'quantity'])
            ->andReturn([
                'name' => 'Bimbim Poca',
                'price' => 5000,
                'description' => 'Ngon giòn rụm',
                'quantity' => 10,
            ]);
        $request->shouldReceive('file')
            ->once()
            ->with('image')
            ->andReturn(UploadedFile::fake()->create('bimbim.jpg', 100));


        // 5. Tạo action
        $action = new StoreAction($useCaseMock, $responder);

        // 6. Gọi action
        $response = $action($request);

        // 7. Assertions
        $this->assertEquals(201, $response->status());
        $data = $response->getData(true);
        $this->assertEquals('Thêm sản phẩm thành công', $data['message']);
    }

    /** @test */
    public function store_returns_500_on_exception()
    {
        Log::shouldReceive('error')->once();

        // Mock UseCase ném exception
        $useCaseMock = Mockery::mock(CreateProductUseCase::class);
        $useCaseMock->shouldReceive('__invoke')
            ->andThrow(new \Exception('Lỗi giả lập'));

        $responder = new StoreProductResponder();

        $request = Mockery::mock(StoreProductRequest::class);
        $request->shouldReceive('only')->andReturn([]);
        $request->shouldReceive('file')->andReturnNull();

        $action = new StoreAction($useCaseMock, $responder);

        $response = $action($request);

        $this->assertEquals(500, $response->status());
        $data = $response->getData(true);
        $this->assertEquals('Đã xảy ra lỗi khi tạo sản phẩm', $data['message']);
    }
}
