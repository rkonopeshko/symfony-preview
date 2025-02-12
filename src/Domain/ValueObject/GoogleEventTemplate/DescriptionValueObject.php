<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\GoogleEventTemplate;

use Yokai\DoctrineValueObject\StringValueObject;

class DescriptionValueObject implements StringValueObject
{
    private string $description;

    public function __construct(string $description)
    {
        $this->description = $description;
    }

    public static function fromValue(string $value): static
    {
        return new static($value);
    }

    public function toValue(): string
    {
        return $this->description;
    }

    public function __toString(): string
    {
        return $this->description;
    }
}

