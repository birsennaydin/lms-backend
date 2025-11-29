<?php

namespace LmsBackend\Application;

use DateTimeImmutable;
use LmsBackend\AccessChecker;
use LmsBackend\Repository\CourseRepository;
use LmsBackend\Repository\StudentRepository;
use LmsBackend\Value\ContentId;
use LmsBackend\Value\CourseId;
use LmsBackend\Value\StudentId;

class AccessService
{
    public function __construct(
        private readonly StudentRepository $students,
        private readonly CourseRepository $courses,
        private readonly AccessChecker $checker
    ) {}

    public function canStudentAccessContent(
        StudentId $studentId,
        CourseId $courseId,
        ContentId $contentId,
        DateTimeImmutable $at
    ): bool {
        $student = $this->students->findById($studentId);
        if ($student === null) {
            return false;
        }

        $course = $this->courses->findById($courseId);
        if ($course === null) {
            return false;
        }

        return $this->checker->canAccessById($student, $course, $contentId, $at);
    }
}
