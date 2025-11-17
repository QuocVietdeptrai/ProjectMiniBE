<?php

namespace App\Http\Actions\Api\Order;

use App\Domain\Order\Exception\OrderNotFoundException;
use App\Domain\Order\UseCase\GetOrderUseCase;
use App\Http\Responders\Api\Order\ShowOrderResponder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class ShowAction
{
    public function __construct(
        protected GetOrderUseCase $useCase,
        protected ShowOrderResponder $responder
    ) {}

    public function __invoke(int $id): JsonResponse
    {
        try {
            $order = $this->useCase->execute($id);
            return ($this->responder)($order);
        } catch (OrderNotFoundException $e) {
             return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ], $e->getCode());
        }
    }
}