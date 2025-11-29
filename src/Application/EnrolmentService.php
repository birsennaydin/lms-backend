<?php

namespace LmsBackend\Application;

use LmsBackend\Enrolment;
use LmsBackend\Repository\CourseRepository;
use LmsBackend\Repository\EnrolmentRepository;
use LmsBackend\Repository\StudentRepository;
use LmsBackend\Value\CourseId;
use LmsBackend\Value\DateRange;
use LmsBackend\Value\StudentId;

class EnrolmentService
{
    public function __construct(
        private readonly StudentRepository $students,
        private readonly CourseRepository $courses,
        private readonly EnrolmentRepository $enrolments
    ) {}

    public function enrolStudentInCourse(
        StudentId $studentId,
        CourseId $courseId,
        DateRange $period
    ): ?Enrolment {

        $student = $this->students->findById($studentId);
        $course = $this->courses->findById($courseId);

        if (!$student || !$course) {
            return null;
        }

        $enrolment = new Enrolment($student, $course, $period);

        $student->addEnrolment($enrolment);
        $this->enrolments->save($enrolment);

        return $enrolment;
    }
}
