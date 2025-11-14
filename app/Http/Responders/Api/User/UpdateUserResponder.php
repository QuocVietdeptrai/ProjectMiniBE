<?php

namespace App\Http\Responders\Api\User;

use App\Domain\Auth\Domain\Entity\UserEntity;
use App\Enums\StatusCode;
use App\Http\Resources\Api\User\UserResource;
use Illuminate\Http\JsonResponse;

class UpdateUserResponder
{
    public function __invoke(UserEntity $product): JsonResponse
    {
        return (new UserResource($product))
            ->additional(['message' => 'Cập nhật thành công'])
            ->response()
            ->setStatusCode(StatusCode::OK);
    }
}