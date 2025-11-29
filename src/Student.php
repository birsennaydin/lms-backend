<?php

namespace LmsBackend;

use LmsBackend\Value\StudentId;

class Student
{
    /** @var Enrolment[] */
    private array $enrolments = [];
    private StudentId $id;

    public function __construct()
    {
        $this->id = StudentId::new();
    }

    public function getId(): StudentId
    {
        return $this->id;
    }

    public function addEnrolment(Enrolment $enrolment): void
    {
        $this->enrolments[] = $enrolment;
    }

    /** @return Enrolment[] */
    public function getEnrolments(): array
    {
        return $this->enrolments;
    }
}
