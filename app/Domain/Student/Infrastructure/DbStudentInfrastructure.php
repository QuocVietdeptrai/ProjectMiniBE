<?php

namespace App\Domain\Student\Infrastructure;

use App\Domain\Student\Domain\Entity\StudentEntity;
use App\Domain\Student\Domain\Repository\StudentRepository;
use App\Models\Student;
use Illuminate\Pagination\LengthAwarePaginator;

class DbStudentInfrastructure implements StudentRepository
{
    public function count(): int
    {
        return Student::count();
    }
    public function paginate(?string $search, int $perPage = 5): LengthAwarePaginator
    {
        $query = Student::query();
        if($search){
            $query->where('full_name','like', "%{$search}%");
        }
        return $query->orderBy('created_at','desc')->paginate($perPage);
    }

    private function toEntity(Student $model): StudentEntity
    {
        return new StudentEntity(
            id: $model->id,
            full_name: $model->full_name,
            dob: $model->dob,
            gender: $model->gender,
            email: $model->email,
            phone: $model->phone,
            class: $model->class,
            avatar: $model->avatar,
            created_at: $model->created_at,
            updated_at: $model->updated_at
        );
    }

    public function all(?string $search): array
    {
        $query = Student::query();
        if($search){
            $query->where('full_name','like',"%{$search}%");
        }
        return $query->orderBy('created_at','desc')->get()->map(fn($m) => $this->toEntity($m))->toArray();
    }

    public function create(StudentEntity $entity): StudentEntity
    {
        $model = Student::create([
            'full_name' => $entity->full_name,
            'dob' => $entity->dob,
            'gender' => $entity->gender,
            'email' => $entity->email,
            'phone' => $entity->phone,
            'class' => $entity->class,
            'avatar' => $entity->avatar,
        ]);
        return $this->toEntity($model);
    }
    public function find(int $id): ?StudentEntity
    {
        $model = Student::find($id);
        return $model ? $this->toEntity($model) : null;
    }
    public function update(int $id, StudentEntity $entity): ?StudentEntity
    {
        $model = Student::find($id);
        if (!$model) return null;

        $model->update([
            'full_name' => $entity->full_name,
            'dob' => $entity->dob,
            'gender' => $entity->gender,
            'email' => $entity->email,
            'phone' => $entity->phone,
            'class' => $entity->class,
            'avatar' => $entity->avatar,
        ]);

        $model->refresh();
        return $this->toEntity($model);
    }
    
    public function delete(int $id): bool
    {
        $model = Student::find($id);
        return $model ? $model->delete() : false;
    }
}