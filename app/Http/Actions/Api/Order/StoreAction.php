<?php

namespace App\Http\Actions\Api\Order;

use App\Domain\Order\UseCase\CreateOrderUseCase;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Responders\Api\Order\StoreOrderResponder;
use Illuminate\Http\JsonResponse;

class StoreAction
{
    public function __construct(
        protected CreateOrderUseCase $useCase,
        protected StoreOrderResponder $responder
    ) {}

    public function __invoke(StoreOrderRequest $request): JsonResponse
    {
        $order = $this->useCase->execute($request->validated());
        return ($this->responder)($order);
    }
}