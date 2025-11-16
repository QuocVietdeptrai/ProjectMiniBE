<?php

namespace App\Http\Actions\Api\Order;

use App\Domain\Order\UseCase\DeleteOrderUseCase;
use App\Http\Responders\Api\Order\DestroyOrderResponder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class DestroyAction
{
    public function __construct(
        protected DeleteOrderUseCase $useCase,
        protected DestroyOrderResponder $responder
    ) {}

    public function __invoke(int $id): JsonResponse
    {
        try {
            $this->useCase->execute($id);
            return ($this->responder)();
        } catch (ModelNotFoundException $e) {
            return $this->responder->notFound($e->getMessage());
        }
    }
}