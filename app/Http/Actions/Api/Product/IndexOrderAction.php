<?php
// File: app/Http/Actions/Api/Product/IndexOrderAction.php

namespace App\Http\Actions\Api\Product;

use App\Domain\Product\UseCase\ListProductForOrderUseCase;
use App\Http\Responders\Api\Product\ListOrderProductResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IndexOrderAction
{
    public function __construct(
        private ListProductForOrderUseCase $useCase,
        private ListOrderProductResponder $responder
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $products = ($this->useCase)($request->search);
        return ($this->responder)($products);
    }
}