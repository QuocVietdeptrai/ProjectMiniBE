<?php

namespace Tests\Unit\Http\Actions\Api\Product;

use App\Domain\Product\UseCase\ListProductForOrderUseCase;
use App\Http\Actions\Api\Product\IndexOrderAction;
use App\Http\Responders\Api\Product\ListOrderProductResponder;
use Illuminate\Http\Request;
use Tests\TestCase;
use Mockery;

class IndexOrderActionTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function index_success()
    {
        // Mock dữ liệu sản phẩm
        $products = [
            (object)[
                'id' => 1,
                'name' => 'Bimbim Poca',
                'price' => 5000,
                'description' => 'Ngon giòn rụm',
                'quantity' => 10,
                'image' => 'imagebimbim.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            (object)[
                'id' => 2,
                'name' => 'Coca Cola',
                'price' => 15000,
                'description' => 'Nước giải khát có gas',
                'quantity' => 20,
                'image' => 'imagecocacola.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];



        // Mock UseCase
        $useCaseMock = Mockery::mock(ListProductForOrderUseCase::class);
        $useCaseMock->shouldReceive('__invoke')
            ->once()
            ->with('') // search query rỗng
            ->andReturn($products);

        // Responder thật
        $responder = new ListOrderProductResponder();

        // Tạo instance của action
        $action = new IndexOrderAction($useCaseMock, $responder);

        // Request giả lập
        $request = Request::create('/api/products/list', 'GET', ['search' => '']);
        $response = $action($request);

        // Test response
        $this->assertEquals(200, $response->status());
        $data = $response->getData(true);
        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('success', $data['status']);
        $this->assertArrayHasKey('data', $data);
        $this->assertCount(2, $data['data']);
    }
}
