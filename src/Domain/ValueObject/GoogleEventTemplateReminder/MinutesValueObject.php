<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\GoogleEventTemplateReminder;

use Webmozart\Assert\Assert;
use Yokai\DoctrineValueObject\IntegerValueObject;

readonly class MinutesValueObject implements IntegerValueObject
{
    public function __construct(private int $minutes)
    {
    }

    public static function fromValue(int $value): static
    {
        Assert::positiveInteger($value);
        return new static($value);
    }

    public function toValue(): int
    {
        return $this->minutes;
    }
    public function __toString(): string
    {
        return (string)$this->minutes;
    }
}
