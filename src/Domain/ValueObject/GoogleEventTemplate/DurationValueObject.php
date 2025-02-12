<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\GoogleEventTemplate;

use Webmozart\Assert\Assert;
use Yokai\DoctrineValueObject\IntegerValueObject;

readonly class DurationValueObject implements IntegerValueObject
{
    public function __construct(private int $duration)
    {

    }

    public static function fromValue(int $value): static
    {
        Assert::positiveInteger($value);
        return new static($value);
    }

    public function toValue(): int
    {
        return $this->duration;
    }
    public function __toString(): string
    {
        return (string)$this->duration;
    }
}
