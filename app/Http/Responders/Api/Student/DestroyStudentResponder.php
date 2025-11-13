<?php 

namespace App\Http\Responders\Api\Student;

use App\Enums\StatusCode;
use Illuminate\Http\JsonResponse;

class DestroyStudentResponder
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Xóa sinh viên thành công'
        ],StatusCode::OK);
    }
}