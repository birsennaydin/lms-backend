<?php

namespace LmsBackend;

use DateTimeImmutable;
use LmsBackend\Value\ContentId;

class Homework implements ContentItem
{
    private ?Course $course = null;
    private readonly ContentId $id;

    public function __construct(
        private readonly string $title
    ) {
        $this->id = ContentId::new();
    }

    public function getId(): ContentId
    {
        return $this->id;
    }

    public function assignToCourse(Course $course): void
    {
        $this->course = $course;
    }

    public function isAvailableAt(Course $course, DateTimeImmutable $at): bool
    {
        return $at >= $course->getStartDate();
    }
}
