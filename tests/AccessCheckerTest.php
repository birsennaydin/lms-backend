<?php

namespace LmsBackend\Tests;

use LmsBackend\AccessChecker;
use LmsBackend\Course;
use LmsBackend\Enrolment;
use LmsBackend\Homework;
use LmsBackend\Lesson;
use LmsBackend\PrepMaterial;
use LmsBackend\Student;
use LmsBackend\Value\DateRange;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;

class AccessCheckerTest extends TestCase
{
    public function test_cannot_access_content_before_course_start()
    {
        $student = new Student();

        $course = new Course(
            new DateRange(
                new DateTimeImmutable('2025-05-13'),
                null
            )
        );

        $lesson = new Lesson(
            new DateTimeImmutable('2025-05-15 10:00')
        );

        $checker = new AccessChecker();

        $canAccess = $checker->canAccess(
            $student,
            $course,
            $lesson,
            new DateTimeImmutable('2025-05-01')
        );

        $this->assertFalse($canAccess);
    }
    public function test_cannot_access_if_student_not_enrolled()
    {
        $student = new Student();
        $course = new Course(
            new DateRange(
                new DateTimeImmutable('2025-05-13'),
                new DateTimeImmutable('2025-06-12')
            )
        );

        $lesson = new Lesson(
            new DateTimeImmutable('2025-05-15 10:00')
        );

        $checker = new AccessChecker();

        $canAccess = $checker->canAccess(
            $student,
            $course,
            $lesson,
            new DateTimeImmutable('2025-05-20')
        );

        $this->assertFalse($canAccess);
    }
    public function test_cannot_access_lesson_before_its_scheduled_time()
    {
        $student = new Student();
        $course = new Course(
            new DateRange(
                new DateTimeImmutable('2025-05-13'),
                new DateTimeImmutable('2025-06-12')
            )
        );

        $lesson = new Lesson(
            new DateTimeImmutable('2025-05-15 10:00')
        );

        $enrolment = new Enrolment(
            $student,
            $course,
            new DateRange(
                new DateTimeImmutable('2025-05-10'),
                new DateTimeImmutable('2025-06-12')
            )
        );

        $student->addEnrolment($enrolment);

        $checker = new AccessChecker();

        $canAccess = $checker->canAccess(
            $student,
            $course,
            $lesson,
            new DateTimeImmutable('2025-05-15 09:59') // 1 minute before
        );

        $this->assertFalse($canAccess);
    }
    public function test_can_access_prep_material_after_course_start()
    {
        $student = new Student();
        $course = new Course(
            new DateRange(
                new DateTimeImmutable('2025-05-13'),
                new DateTimeImmutable('2025-06-12')
            )
        );

        $prep = new PrepMaterial("Reading Guide");

        // Student is properly enrolled for entire course
        $enrolment = new Enrolment(
            $student,
            $course,
            new DateRange(
                new DateTimeImmutable('2025-05-10'),
                new DateTimeImmutable('2025-06-12')
            )
        );

        $student->addEnrolment($enrolment);

        $checker = new AccessChecker();

        $canAccess = $checker->canAccess(
            $student,
            $course,
            $prep,
            new DateTimeImmutable('2025-05-13 00:01')
        );

        $this->assertTrue($canAccess);
    }
    public function test_can_access_homework_after_course_start()
    {
        $student = new Student();

        $course = new Course(
            new DateRange(
                new DateTimeImmutable('2025-05-13'),
                new DateTimeImmutable('2025-06-12')
            )
        );

        $homework = new Homework("Label a Plant Cell");

        $enrolment = new Enrolment(
            $student,
            $course,
            new DateRange(
                new DateTimeImmutable('2025-05-10'),
                new DateTimeImmutable('2025-06-12')
            )
        );

        $student->addEnrolment($enrolment);

        $checker = new AccessChecker();

        $canAccess = $checker->canAccess(
            $student,
            $course,
            $homework,
            new DateTimeImmutable('2025-05-13 00:01')
        );

        $this->assertTrue($canAccess);
    }
    public function test_can_access_by_content_id()
    {
        $student = new Student();

        $course = new Course(
            new DateRange(
                new \DateTimeImmutable('2025-05-13'),
                new \DateTimeImmutable('2025-06-12')
            )
        );

        $lesson = new Lesson(
            new \DateTimeImmutable('2025-05-15 10:00')
        );

        $course->addContent($lesson);

        $enrolment = new Enrolment(
            $student,
            $course,
            new DateRange(
                new \DateTimeImmutable('2025-05-10'),
                new \DateTimeImmutable('2025-06-12')
            )
        );

        $student->addEnrolment($enrolment);

        $checker = new AccessChecker();

        $canAccess = $checker->canAccessById(
            $student,
            $course,
            $lesson->getId(),
            new \DateTimeImmutable('2025-05-16')
        );

        $this->assertTrue($canAccess);
    }
}
