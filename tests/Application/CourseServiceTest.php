<?php

namespace LmsBackend\Tests\Application;

use DateTimeImmutable;
use LmsBackend\Application\CourseService;
use LmsBackend\Lesson;
use LmsBackend\Repository\InMemory\InMemoryCourseRepository;
use LmsBackend\Value\DateRange;
use PHPUnit\Framework\TestCase;

class CourseServiceTest extends TestCase
{
    public function test_create_course_and_add_content()
    {
        $repo = new InMemoryCourseRepository();
        $service = new CourseService($repo);

        $course = $service->createCourse(
            new DateRange(
                new DateTimeImmutable('2025-01-10'),
                new DateTimeImmutable('2025-12-31')
            )
        );

        $lesson = new Lesson(new DateTimeImmutable('2025-02-15 09:00'));

        $service->addContentToCourse($course, $lesson);

        $saved = $repo->findById($course->getId());

        $this->assertNotNull($saved);
        $this->assertCount(1, $saved->getLessons());
    }
}
