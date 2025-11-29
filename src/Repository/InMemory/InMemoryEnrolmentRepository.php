<?php

namespace LmsBackend\Repository\InMemory;

use LmsBackend\Enrolment;
use LmsBackend\Repository\EnrolmentRepository;
use LmsBackend\Value\EnrolmentId;

class InMemoryEnrolmentRepository implements EnrolmentRepository
{
    /** @var array<string, Enrolment> */
    private array $items = [];

    public function save(Enrolment $enrolment): void
    {
        $this->items[(string)$enrolment->getId()] = $enrolment;
    }

    public function findById(EnrolmentId $id): ?Enrolment
    {
        return $this->items[(string)$id] ?? null;
    }

    /**
     * @return Enrolment[]
     */
    public function findAll(): array
    {
        return array_values($this->items);
    }
}
