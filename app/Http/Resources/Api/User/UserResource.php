<?php 

namespace App\Http\Resources\Api\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'name'=> $this->resource->name,
            'email' => $this->resource->email,
            'role' => $this->resource->role,
            'phone' => $this->resource->phone,
            'address' => $this->resource->address,
            'image' => $this->resource->image,
            'created_at' => $this->resource->created_at,
            'last_login_at' => $this->resource->last_login_at,
            'status' => $this->resource->status,
        ];
    }
}
