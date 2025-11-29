<?php

namespace LmsBackend\Repository;

use LmsBackend\Enrolment;
use LmsBackend\Value\EnrolmentId;

interface EnrolmentRepository
{
    public function save(Enrolment $enrolment): void;

    public function findById(EnrolmentId $id): ?Enrolment;
}
