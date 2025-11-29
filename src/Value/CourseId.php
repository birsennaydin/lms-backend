<?php

namespace LmsBackend\Value;

use Ramsey\Uuid\Uuid;

final class CourseId
{
    private function __construct(
        public readonly string $value
    ) {}

    public static function new(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function equals(CourseId $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
