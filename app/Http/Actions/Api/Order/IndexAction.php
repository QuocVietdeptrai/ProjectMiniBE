<?php

namespace App\Http\Actions\Api\Order;

use App\Domain\Order\UseCase\ListOrderUseCase;
use App\Http\Responders\Api\Order\ListOrderResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IndexAction
{
    public function __construct(
        protected ListOrderUseCase $useCase,
        protected ListOrderResponder $responder
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $filters = $request->only('search');
        $perPage = $request->get('per_page', 5);

        $orders = $this->useCase->execute($filters, $perPage);

        return ($this->responder)($orders);
    }
}