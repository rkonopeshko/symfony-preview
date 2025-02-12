<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\GoogleEventTemplate;

use Yokai\DoctrineValueObject\StringValueObject;

class TitleValueObject implements StringValueObject
{
    private string $title;

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public static function fromValue(string $value): static
    {
        return new static($value);
    }

    public function toValue(): string
    {
        return $this->title;
    }

    public function __toString(): string
    {
        return $this->title;
    }
}