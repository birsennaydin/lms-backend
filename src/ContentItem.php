<?php

namespace LmsBackend;

use DateTimeImmutable;
use LmsBackend\Value\ContentId;

interface ContentItem
{
    public function getId(): ContentId;

    public function isAvailableAt(Course $course, DateTimeImmutable $at): bool;

    public function assignToCourse(Course $course): void;
}