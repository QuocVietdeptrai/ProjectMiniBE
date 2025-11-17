<?php 

namespace App\Http\Actions\Api\Student;

use App\Domain\Student\Exception\StudentNotFoundException;
use App\Domain\Student\UseCase\UpdateStudentUseCase;
use App\Enums\StatusCode;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Http\Responders\Api\Student\UpdateStudentResponder;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class UpdateAction
{
    public function __construct(
        private UpdateStudentUseCase $useCase,
        private UpdateStudentResponder $responder
    ){}

    public function __invoke(UpdateStudentRequest $request,int $id): JsonResponse
    {
        try {
            $student = ($this->useCase)(
                $id,
                $request->only(['full_name','dob','gender','email','phone','class']),
                $request->file('avatar')
            );
            // Log::info('Cập nhật sinh viên thành công: ID ' . $id);
            return ($this->responder)($student);
        } catch (Exception $e) {
            Log::info('Lỗi cập nhật sản phẩm: ' . $e->getMessage());
            return response()->json(['message' => 'Đã xảy ra lỗi khi cập nhật sản phẩm'], StatusCode::INTERNAL_ERR);
        }
    }
}