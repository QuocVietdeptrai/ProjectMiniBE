<?php

namespace App\Http\Resources\Api\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class TokenResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'code' => 'success',
            'access_token' => $this->resource->token,
            'message' => $this->resource->message
        ];
    }
}