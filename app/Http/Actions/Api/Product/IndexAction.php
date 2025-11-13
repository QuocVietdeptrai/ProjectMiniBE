<?php
// File: app/Http/Actions/Api/Product/IndexAction.php

namespace App\Http\Actions\Api\Product;

use App\Domain\Product\UseCase\ListProductUseCase;
use App\Http\Responders\Api\Product\ListProductResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IndexAction
{
    public function __construct(
        private ListProductUseCase $useCase,
        private ListProductResponder $responder
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $products = ($this->useCase)($request->search);
        return ($this->responder)($products);
    }
}