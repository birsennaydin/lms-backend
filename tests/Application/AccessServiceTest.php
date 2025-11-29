<?php

namespace LmsBackend\Tests\Application;

use DateTimeImmutable;
use LmsBackend\AccessChecker;
use LmsBackend\Application\AccessService;
use LmsBackend\Application\EnrolmentService;
use LmsBackend\Application\CourseService;
use LmsBackend\Course;
use LmsBackend\Lesson;
use LmsBackend\Repository\InMemory\InMemoryCourseRepository;
use LmsBackend\Repository\InMemory\InMemoryEnrolmentRepository;
use LmsBackend\Repository\InMemory\InMemoryStudentRepository;
use LmsBackend\Student;
use LmsBackend\Value\DateRange;
use PHPUnit\Framework\TestCase;

class AccessServiceTest extends TestCase
{
    public function test_student_can_access_lesson_by_id()
    {
        // Repositories
        $students = new InMemoryStudentRepository();
        $courses  = new InMemoryCourseRepository();
        $enrolments = new InMemoryEnrolmentRepository();

        // Services
        $courseService = new CourseService($courses);
        $enrolmentService = new EnrolmentService($students, $courses, $enrolments);
        $accessService = new AccessService($students, $courses, new AccessChecker());

        // Create student
        $student = new Student();
        $students->save($student);

        // Create course
        $course = $courseService->createCourse(
            new DateRange(
                new DateTimeImmutable('2025-01-10'),
                new DateTimeImmutable('2025-12-10')
            )
        );

        // Create content
        $lesson = new Lesson(new DateTimeImmutable('2025-03-01 10:00'));
        $courseService->addContentToCourse($course, $lesson);

        // Enrol student
        $enrolmentService->enrolStudentInCourse(
            $student->getId(),
            $course->getId(),
            new DateRange(
                new DateTimeImmutable('2025-01-01'),
                new DateTimeImmutable('2025-12-31')
            )
        );

        $canAccess = $accessService->canStudentAccessContent(
            $student->getId(),
            $course->getId(),
            $lesson->getId(),
            new DateTimeImmutable('2025-03-01 11:00')
        );

        $this->assertTrue($canAccess);
    }
}
