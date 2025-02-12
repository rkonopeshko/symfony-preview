<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\GoogleEventTemplate;

use Yokai\DoctrineValueObject\StringValueObject;

class CreatorIdValueObject implements StringValueObject
{
    private string $creatorId;

    public function __construct(string $creatorId)
    {
        $this->creatorId = $creatorId;
    }

    public static function fromValue(string $value): static
    {
        return new static($value);
    }

    public function toValue(): string
    {
        return $this->creatorId;
    }

    public function __toString(): string
    {
        return $this->creatorId;
    }
}
