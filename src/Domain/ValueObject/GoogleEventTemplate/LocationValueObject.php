<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\GoogleEventTemplate;

use Yokai\DoctrineValueObject\StringValueObject;

class LocationValueObject implements StringValueObject
{
    private string $location;

    public function __construct(string $location)
    {
        $this->location = $location;
    }

    public static function fromValue(string $value): static
    {
        return new static($value);
    }

    public function toValue(): string
    {
        return $this->location;
    }

    public function __toString(): string
    {
        return $this->location;
    }
}