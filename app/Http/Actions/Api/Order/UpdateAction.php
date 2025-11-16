<?php

namespace App\Http\Actions\Api\Order;

use App\Domain\Order\UseCase\UpdateOrderUseCase;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Http\Responders\Api\Order\UpdateOrderResponder;
use Illuminate\Http\JsonResponse;

class UpdateAction
{
    public function __construct(
        protected UpdateOrderUseCase $useCase,
        protected UpdateOrderResponder $responder
    ) {}

    public function __invoke(UpdateOrderRequest $request, int $id): JsonResponse
    {
        try {
            $order = $this->useCase->execute($id, $request->validated());
            return ($this->responder)($order);
        } catch (\Exception $e) {
            return $this->responder->error($e->getMessage());
        }
    }
}