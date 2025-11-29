<?php

namespace LmsBackend;

use DateTimeImmutable;
use LmsBackend\Value\DateRange;
use LmsBackend\Value\EnrolmentId;

class Enrolment
{
    private EnrolmentId $id;

    public function __construct(
        private readonly Student $student,
        private readonly Course $course,
        private readonly DateRange $dateRange
    ) {
        $this->id = EnrolmentId::new();
    }

    public function getId(): EnrolmentId
    {
        return $this->id;
    }

    public function getStudent(): Student
    {
        return $this->student;
    }

    public function getCourse(): Course
    {
        return $this->course;
    }

    public function getDateRange(): DateRange
    {
        return $this->dateRange;
    }

    public function matchesCourse(Course $course): bool
    {
        return $this->course === $course;
    }

    public function isActiveAt(DateTimeImmutable $at): bool
    {
        return $this->dateRange->contains($at);
    }
}
