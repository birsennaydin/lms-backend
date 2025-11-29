<?php

namespace LmsBackend;

use DateTimeImmutable;
use LmsBackend\Value\ContentId;

class AccessChecker
{
    /**
     * Legacy access check based on a ContentItem instance.
     * Prefer canAccessById() when integrating with API / repositories.
     *
     * @deprecated Use canAccessById() instead.
     */
    public function canAccess(
        Student $student,
        Course $course,
        ContentItem $content,
        DateTimeImmutable $at
    ): bool {

        $isEnrolled = false;

        foreach ($student->getEnrolments() as $enrolment) {
            if ($enrolment->matchesCourse($course) && $enrolment->isActiveAt($at)) {
                $isEnrolled = true;
                break;
            }
        }

        if (!$isEnrolled) {
            return false;
        }

        if (!$course->hasStartedAt($at)) {
            return false;
        }

        if ($course->hasEndedAt($at)) {
            return false;
        }

        return $content->isAvailableAt($course, $at);
    }

    public function canAccessById(
        Student $student,
        Course $course,
        ContentId $contentId,
        DateTimeImmutable $at
    ): bool {

        $content = $course->findContent($contentId);

        if ($content === null) {
            return false;
        }

        return $this->canAccess($student, $course, $content, $at);
    }
}
