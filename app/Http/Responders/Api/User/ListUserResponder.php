<?php 

namespace App\Http\Responders\Api\User;

use App\Enums\StatusCode;
use App\Http\Resources\Api\User\UserResource;
use Illuminate\Http\JsonResponse;

class ListUserResponder
{
    public function __invoke($users): JsonResponse
    {
        $data = UserResource::collection($users)->collection;

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'pagination' => [
                'current_page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'last_page' => $users->lastPage(),
            ]
        ],StatusCode::OK);
    }
}