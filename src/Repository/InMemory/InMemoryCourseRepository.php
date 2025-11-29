<?php

namespace LmsBackend\Repository\InMemory;

use LmsBackend\Course;
use LmsBackend\Repository\CourseRepository;
use LmsBackend\Value\CourseId;

class InMemoryCourseRepository implements CourseRepository
{
    /** @var array<string, Course> */
    private array $items = [];

    public function save(Course $course): void
    {
        $this->items[(string)$course->getId()] = $course;
    }

    public function findById(CourseId $id): ?Course
    {
        return $this->items[(string)$id] ?? null;
    }

    public function findAll(): array
    {
        return array_values($this->items);
    }
}
