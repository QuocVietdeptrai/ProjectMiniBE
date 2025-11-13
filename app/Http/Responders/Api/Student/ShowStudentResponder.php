<?php 

namespace App\Http\Responders\Api\Student;

use App\Domain\Student\Domain\Entity\StudentEntity;
use App\Enums\StatusCode;
use App\Http\Resources\Api\Student\StudentResource;
use Illuminate\Http\JsonResponse;

class ShowStudentResponder
{
    public function __invoke(StudentEntity $student): JsonResponse
    {
        return (new StudentResource($student))
            ->response()
            ->setStatusCode(StatusCode::OK);
    }
}