<?php

namespace App\Http\Resources\Api\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'code' => 'success',
            'access_token' => $this->resource['access_token'],
            'user' => $this->resource['user'],
        ];
    }
}