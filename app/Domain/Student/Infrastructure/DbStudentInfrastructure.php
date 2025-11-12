<?php

namespace App\Domain\Student\Infrastructure;

use App\Domain\Student\Domain\Repository\StudentRepository;
use App\Models\Student;

class DbStudentInfrastructure implements StudentRepository
{
    public function count(): int
    {
        return Student::count();
    }
}