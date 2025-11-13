<?php

namespace App\Http\Controllers\Api;

use App\Http\Actions\Api\Product\{
    IndexAction, IndexOrderAction, StoreAction,
    ShowAction, UpdateAction, DestroyAction
};
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;

class ProductController extends Controller
{
    public function index(IndexAction $action, \Illuminate\Http\Request $request)
    {
        return $action($request);
    }

    public function indexOrder(IndexOrderAction $action, \Illuminate\Http\Request $request)
    {
        return $action($request);
    }

    public function store(StoreAction $action, StoreProductRequest $request)
    {
        return $action($request);
    }

    public function show(ShowAction $action, int $id)
    {
        return $action($id);
    }

    public function update(UpdateAction $action, UpdateProductRequest $request, int $id)
    {
        return $action($request, $id);
    }

    public function destroy(DestroyAction $action, int $id)
    {
        return $action($id);
    }
}