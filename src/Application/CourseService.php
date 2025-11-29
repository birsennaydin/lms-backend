<?php

namespace LmsBackend\Application;

use LmsBackend\ContentItem;
use LmsBackend\Course;
use LmsBackend\Repository\CourseRepository;
use LmsBackend\Value\DateRange;

class CourseService
{
    public function __construct(
        private readonly CourseRepository $courses
    ) {}

    public function createCourse(DateRange $range): Course
    {
        $course = new Course($range);
        $this->courses->save($course);
        return $course;
    }

    public function addContentToCourse(Course $course, ContentItem $content): void
    {
        $course->addContent($content);
        $this->courses->save($course);
    }
}
