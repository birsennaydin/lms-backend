<?php

namespace LmsBackend\Repository\InMemory;

use LmsBackend\Repository\StudentRepository;
use LmsBackend\Student;
use LmsBackend\Value\StudentId;

class InMemoryStudentRepository implements StudentRepository
{
    /** @var array<string, Student> */
    private array $items = [];

    public function save(Student $student): void
    {
        $this->items[(string)$student->getId()] = $student;
    }

    public function findById(StudentId $id): ?Student
    {
        return $this->items[(string)$id] ?? null;
    }

    /**
     * @return Student[]
     */
    public function findAll(): array
    {
        return array_values($this->items);
    }
}
