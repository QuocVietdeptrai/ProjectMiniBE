<?php

namespace App\Http\Actions\Api\Order;

use App\Domain\Order\Usecase\ListOrderUseCase;
use App\Http\Responders\Api\Order\ListOrderResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IndexAction
{
    public function __construct(
        private ListOrderUseCase $useCase,
        private ListOrderResponder $responder
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $products = ($this->useCase)($request->search);
        return ($this->responder)($products);
    }
}