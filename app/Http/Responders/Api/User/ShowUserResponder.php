<?php 

namespace App\Http\Responders\Api\User;

use App\Domain\Auth\Domain\Entity\UserEntity;
use App\Enums\StatusCode;
use App\Http\Resources\Api\User\UserResource;
use Illuminate\Http\JsonResponse;

class ShowUserResponder
{
    public function __invoke(UserEntity $user): JsonResponse
    {
        return (new UserResource($user))
            ->response()
            ->setStatusCode(StatusCode::OK);
    }
}