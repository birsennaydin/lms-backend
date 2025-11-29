<?php

namespace LmsBackend;

use DateTimeImmutable;
use LmsBackend\Value\ContentId;

class Lesson implements ContentItem
{
    private ?Course $course = null;
    private readonly ContentId $id;

    public function __construct(
        private readonly DateTimeImmutable $scheduledAt
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

    public function getScheduledAt(): DateTimeImmutable
    {
        return $this->scheduledAt;
    }

    public function isAvailableAt(Course $course, DateTimeImmutable $at): bool
    {
        return $at >= $this->scheduledAt;
    }
}
