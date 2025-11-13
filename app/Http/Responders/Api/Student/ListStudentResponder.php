<?php 

namespace App\Http\Responders\Api\Student;

use App\Enums\StatusCode;
use App\Http\Resources\Api\Student\StudentResource;
use Illuminate\Http\JsonResponse;

class ListStudentResponder
{
    public function __invoke($students): JsonResponse
    {
        $data = StudentResource::collection($students)->collection;
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'pagination' => [
                'current_page' => $students->currentPage(),
                'per_page' => $students->perPage(),
                'total' => $students->total(),
                'last_page' => $students->lastPage(),
            ]
        ],StatusCode::OK);
    }
}