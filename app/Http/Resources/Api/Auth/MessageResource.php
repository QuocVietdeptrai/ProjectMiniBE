<?php

namespace App\Http\Resources\Api\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'code' => $this->resource['code'] ?? 'success',
            'message' => $this->resource['message']
        ];
    }
}