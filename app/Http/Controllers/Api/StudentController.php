<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Models\Student;
use App\Helpers\CloudinaryHelper;
use Exception;
use App\Enums\StatusCode;
use App\Http\Actions\Api\Student\DestroyAction;
use App\Http\Actions\Api\Student\ShowAction;
use App\Http\Actions\Api\Student\IndexAction;
use App\Http\Actions\Api\Student\StoreAction;
use App\Http\Actions\Api\Student\UpdateAction;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(IndexAction $action, Request $request)
    {
        return $action($request);
    }

    // Thêm sinh viên
    public function store(StoreAction $action, StoreStudentRequest $request)
    {
        return $action($request);
    }

    // Xem chi tiết sinh viên
    public function show(ShowAction $action, int $id)
    {
        return $action($id);
    }

    public function update(UpdateAction $action, UpdateStudentRequest $request, int $id)
    {
        return $action($request, $id);
    }

    // Xóa sinh viên
    public function destroy(DestroyAction $action, int $id)
    {
        return $action($id);
    }
}
