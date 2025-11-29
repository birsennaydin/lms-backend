<?php

namespace LmsBackend;

use LmsBackend\Value\CourseId;
use LmsBackend\Value\DateRange;
use LmsBackend\Value\ContentId;
use DateTimeImmutable;

class Course
{
    /** @var ContentItem[] */
    private array $contents = [];
    private CourseId $id;

    public function __construct(DateRange $dateRange)
    {
        $this->id = CourseId::new();
        $this->dateRange = $dateRange;
    }

    public function getId(): CourseId
    {
        return $this->id;
    }

    public function getStartDate(): DateTimeImmutable
    {
        return $this->dateRange->start;
    }

    public function hasStartedAt(DateTimeImmutable $at): bool
    {
        return $this->dateRange->startsBefore($at);
    }

    public function hasEndedAt(DateTimeImmutable $at): bool
    {
        return $this->dateRange->endsBefore($at);
    }

    public function addContent(ContentItem $content): void
    {
        $content->assignToCourse($this);
        $this->contents[] = $content;
    }

    /**
     * @return ContentItem[]
     */
    public function getContents(): array
    {
        return $this->contents;
    }

    /**
     * @param ContentId $id
     * @return ContentItem|null
     */
    public function findContent(ContentId $id): ?ContentItem
    {
        foreach ($this->contents as $content) {
            if ($content->getId()->equals($id)) {
                return $content;
            }
        }
        return null;
    }

    /**
     * @return Lesson[]
     */
    public function getLessons(): array
    {
        return array_filter($this->contents, fn($c) => $c instanceof Lesson);
    }

    /**
     * @return Homework[]
     */
    public function getHomeworks(): array
    {
        return array_filter($this->contents, fn($c) => $c instanceof Homework);
    }

    /**
     * @return PrepMaterial[]
     */
    public function getPrepMaterials(): array
    {
        return array_filter($this->contents, fn($c) => $c instanceof PrepMaterial);
    }

    /**
     *
     * @return ContentItem[]
     */
    public function getContentsByType(string $class): array
    {
        return array_filter($this->contents, fn($c) => $c instanceof $class);
    }
}
