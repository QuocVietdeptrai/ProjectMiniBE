<?php 

namespace App\Http\Responders\Api\Student;

use App\Domain\Student\Domain\Entity\StudentEntity;
use App\Enums\StatusCode;
use App\Http\Resources\Api\Student\StudentResource;
use Illuminate\Http\JsonResponse;

class UpdateStudentResponder
{
    public function __invoke(StudentEntity $student): JsonResponse
    {
        return (new StudentResource($student))
            ->additional([
                'status' => 'success',
                'message' => 'Cập nhật sinh viên thành công'
            ])
            ->response()
            ->setStatusCode(StatusCode::OK);
    }
}