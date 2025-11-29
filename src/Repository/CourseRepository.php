<?php

namespace LmsBackend\Repository;

use LmsBackend\Course;
use LmsBackend\Value\CourseId;

interface CourseRepository
{
    public function save(Course $course): void;

    public function findById(CourseId $id): ?Course;

    /**
     * @return Course[]
     */
    public function findAll(): array;
}
