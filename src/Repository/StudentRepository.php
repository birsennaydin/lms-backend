<?php

namespace LmsBackend\Repository;

use LmsBackend\Student;
use LmsBackend\Value\StudentId;

interface StudentRepository
{
    public function save(Student $student): void;

    public function findById(StudentId $id): ?Student;
}
