<?php

namespace Tests\Unit\Http\Actions\Api\Product;

use App\Domain\Product\UseCase\ListProductUseCase;
use App\Http\Actions\Api\Product\IndexAction;
use App\Http\Responders\Api\Product\ListProductResponder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;
use Mockery;

class IndexActionTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
    /** @test */
    // Trả về phản hồi JSON với danh sách sản phẩm
    public function IndexSuccess()
    {
        // Tạo mock cho use case
        $products = new LengthAwarePaginator(
            collect([
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
            ]),
            2, // total
            10, // per page
            1 // current page
        );

        $useCaseMock = Mockery::mock(ListProductUseCase::class);
        $useCaseMock->shouldReceive('__invoke')
            ->once()
            ->with('') // search query rỗng
            ->andReturn($products);

        $responderMock = Mockery::mock(ListProductResponder::class);
        $responderMock->shouldReceive('__invoke')
            ->once()
            ->with($products)
            ->andReturnUsing(function($products) {
                return response()->json([
                    'status' => 'success',
                    'data' => $products->items(),
                    'pagination' => [
                        'current_page' => $products->currentPage(),
                        'per_page' => $products->perPage(),
                        'total' => $products->total(),
                        'last_page' => $products->lastPage(),
                    ],
                ]);
            });

        $action = new IndexAction($useCaseMock, $responderMock);

        $request = Request::create('/api/products/list', 'GET', ['search' => '']);

        $response = $action($request);

        $this->assertEquals(200, $response->status());
        $this->assertArrayHasKey('status', $response->getData(true));
        $this->assertEquals('success', $response->getData(true)['status']);
        $this->assertCount(2, $response->getData(true)['data']);
        $this->assertArrayHasKey('pagination', $response->getData(true));
    }
}
