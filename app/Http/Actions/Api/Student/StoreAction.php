<?php
// File: app/Http/Actions/Api/Product/StoreAction.php

namespace App\Http\Actions\Api\Student;
use App\Domain\Student\UseCase\CreateStudentUseCase;
use App\Enums\StatusCode;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Responders\Api\Student\StoreStudentResponder;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\Log;

class StoreAction
{
    public function __construct(
        private CreateStudentUseCase $useCase,
        private StoreStudentResponder $successResponder,
    ) {}

    public function __invoke(StoreStudentRequest $request): JsonResponse
    {
        try {
            $product = ($this->useCase)(
                $request->only(['full_name', 'dob', 'gender', 'email', 'phone', 'class']),
                $request->file('avatar')
            );

            return ($this->successResponder)($product);
        } catch (Exception $e) {
            Log::error('Lỗi tạo sản phẩm: ' . $e->getMessage());
            return response()->json(['message' => 'Đã xảy ra lỗi khi tạo học sinh'], StatusCode::INTERNAL_ERR);
        }
    }
}