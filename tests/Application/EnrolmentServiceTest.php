<?php

namespace LmsBackend\Tests\Application;

use DateTimeImmutable;
use LmsBackend\Application\EnrolmentService;
use LmsBackend\Course;
use LmsBackend\Repository\InMemory\InMemoryCourseRepository;
use LmsBackend\Repository\InMemory\InMemoryEnrolmentRepository;
use LmsBackend\Repository\InMemory\InMemoryStudentRepository;
use LmsBackend\Student;
use LmsBackend\Value\DateRange;
use PHPUnit\Framework\TestCase;

class EnrolmentServiceTest extends TestCase
{
    public function test_student_is_enrolled_in_course()
    {
        $students = new InMemoryStudentRepository();
        $courses = new InMemoryCourseRepository();
        $enrolments = new InMemoryEnrolmentRepository();

        $service = new EnrolmentService($students, $courses, $enrolments);

        $student = new Student();
        $students->save($student);

        $course = new Course(
            new DateRange(new DateTimeImmutable('2025-01-01'), new DateTimeImmutable('2025-12-01'))
        );
        $courses->save($course);

        $period = new DateRange(
            new DateTimeImmutable('2025-01-01'),
            new DateTimeImmutable('2025-12-01')
        );

        $enrolment = $service->enrolStudentInCourse(
            $student->getId(),
            $course->getId(),
            $period
        );

        $this->assertNotNull($enrolment);
        $this->assertSame($student, $enrolment->getStudent());
        $this->assertSame($course, $enrolment->getCourse());
    }
}
