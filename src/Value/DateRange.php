<?php

namespace LmsBackend\Value;

use DateTimeImmutable;

final class DateRange
{
    public function __construct(
        public readonly DateTimeImmutable $start,
        public readonly ?DateTimeImmutable $end = null,
    ) {
        if ($this->end !== null && $this->end < $this->start) {
            throw new \InvalidArgumentException('End date cannot be before start date');
        }
    }

    public function contains(DateTimeImmutable $at): bool
    {
        if ($at < $this->start) {
            return false;
        }

        if ($this->end !== null && $at > $this->end) {
            return false;
        }

        return true;
    }

    public function startsBefore(DateTimeImmutable $at): bool
    {
        return $at >= $this->start;
    }

    public function endsBefore(DateTimeImmutable $at): bool
    {
        return $this->end !== null && $at > $this->end;
    }
}
