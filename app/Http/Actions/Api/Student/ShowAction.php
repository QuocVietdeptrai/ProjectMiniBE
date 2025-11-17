<?php 

namespace App\Http\Actions\Api\Student;

use App\Domain\Student\Exception\StudentNotFoundException;
use App\Domain\Student\UseCase\GetStudentUseCase;
use App\Enums\StatusCode;
use App\Http\Responders\Api\Student\ShowStudentResponder;
use Illuminate\Http\JsonResponse;

class ShowAction
{
    public function __construct(
        private GetStudentUseCase $useCase,
        private ShowStudentResponder $responder,
    ){}

    public function __invoke(int $id): JsonResponse
    {
        try {
            $student = ($this->useCase)($id);
            return ($this->responder)($student);
        } catch (StudentNotFoundException $e) {
             return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ], $e->getCode());
        }
    }
}