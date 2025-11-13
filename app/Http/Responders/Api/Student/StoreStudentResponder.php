<?php 

namespace App\Http\Responders\Api\Student;

use App\Domain\Student\Domain\Entity\StudentEntity;
use App\Enums\StatusCode;
use App\Http\Resources\Api\Student\StudentResource;
use Illuminate\Http\JsonResponse;

class StoreStudentResponder
{
    public function __invoke(StudentEntity $students): JsonResponse
    {
        return (new StudentResource($students))
            ->additional(['message' => 'Thêm sinh viên thành công'])
            ->response()
            ->setStatusCode(StatusCode::CREATED);
    }
}