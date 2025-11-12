<?php

namespace Tests\Unit\Http\Actions\Api\Dashboard;

use App\Http\Actions\Api\Dashboard\SummaryAction;
use App\Domain\Dashboard\UseCase\DashboardSummaryUseCase;
use Illuminate\Http\JsonResponse;
use Mockery;
use Tests\TestCase;

class SummaryActionTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
    /** @test */
    // Trả về dữ liệu tóm tắt từ DashboardSummaryUseCase
    public function SummaryTest()
    {
        $summaryData = [
            'products' => 10,
            'students' => 20,
            'orders' => 30,
        ];

        $useCaseMock = Mockery::mock(DashboardSummaryUseCase::class);
        $useCaseMock->shouldReceive('__invoke')
                    ->once()
                    ->andReturn($summaryData);

        $action = new SummaryAction($useCaseMock);

        $response = $action();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($summaryData, $response->getData(true));
    }
}
