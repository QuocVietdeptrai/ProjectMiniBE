<?php 

namespace App\Http\Resources\Api\Student;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'full_name' => $this->resource->full_name,
            'dob' => $this->resource->dob,
            'gender' => $this->resource->gender,
            'email' => $this->resource->email,
            'phone' => $this->resource->phone,
            'class' => $this->resource->class,
            'avatar' => $this->resource->avatar,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    } 
}