<?php

namespace App\Http\Responders\Api\Auth;

use App\Http\Resources\Api\Auth\MessageResource;

class MessageResponder
{
    public function __invoke(array $data): MessageResource
    {
        return new MessageResource($data);
    }
}