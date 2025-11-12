<?php

namespace App\Http\Actions\Api\Dashboard;

use App\Domain\Dashboard\UseCase\DashboardSummaryUseCase;
use Illuminate\Http\JsonResponse;

class SummaryAction
{
    public function __construct(
        private DashboardSummaryUseCase $useCase
    ) {}

    public function __invoke(): JsonResponse
    {
        $data = ($this->useCase)();
        return response()->json($data);
    }
}