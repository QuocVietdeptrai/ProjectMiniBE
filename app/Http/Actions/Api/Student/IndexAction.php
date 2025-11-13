<?php 

namespace App\Http\Actions\Api\Student;

use App\Domain\Student\UseCase\ListStudentUseCase;
use App\Http\Responders\Api\Student\ListStudentResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IndexAction
{
    public function __construct(
        private ListStudentUseCase $useCase,
        private ListStudentResponder $responder
    ){}
    public function __invoke(Request $request): JsonResponse
    {
        $students = ($this->useCase)($request->search);
        return ($this->responder)($students);
    }
}