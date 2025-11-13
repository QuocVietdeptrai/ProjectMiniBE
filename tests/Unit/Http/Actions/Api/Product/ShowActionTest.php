<?php

namespace Tests\Unit\Http\Actions\Api\Product;

use App\Domain\Product\UseCase\GetProductUseCase;
use App\Http\Actions\Api\Product\ShowAction;
use App\Http\Responders\Api\Product\ShowProductResponder;
use App\Domain\Product\Domain\Entity\ProductEntity;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;
use Mockery;

class ShowActionTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function show_success_returns_product()
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
        $useCaseMock = Mockery::mock(GetProductUseCase::class);
        $useCaseMock->shouldReceive('__invoke')
            ->once()
            ->with(1)
            ->andReturn($product);

        // 3. Responder thật
        $responder = new ShowProductResponder();

        // 4. Action
        $action = new ShowAction($useCaseMock, $responder);

        // 5. Gọi action
        $response = $action(1);

        // 6. Assertions
        $this->assertEquals(200, $response->status());

        $data = $response->getData(true);
        $this->assertArrayHasKey('id', $data);
        $this->assertEquals(1, $data['id']);
        $this->assertEquals('Bimbim Poca', $data['name']);
    }

    /** @test */
    public function show_returns_404_if_not_found()
    {
        $useCaseMock = Mockery::mock(GetProductUseCase::class);
        $useCaseMock->shouldReceive('__invoke')
            ->once()
            ->with(999)
            ->andThrow(ModelNotFoundException::class);

        $responder = new ShowProductResponder();
        $action = new ShowAction($useCaseMock, $responder);

        // 2. Gọi action
        $response = $action(999);

        // 3. Assertions
        $this->assertEquals(404, $response->status());
        $data = $response->getData(true);
        $this->assertEquals('Sản phẩm không tồn tại', $data['message']);
    }
}
