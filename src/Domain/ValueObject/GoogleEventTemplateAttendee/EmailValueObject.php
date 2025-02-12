<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\GoogleEventTemplateAttendee;

use Webmozart\Assert\Assert;
use Yokai\DoctrineValueObject\StringValueObject;

class EmailValueObject implements StringValueObject
{
    private string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public static function fromValue(string $value): static
    {
        Assert::email($value);
        return new static($value);
    }

    public function toValue(): string
    {
        return $this->email;
    }

    public function __toString(): string
    {
        return $this->email;
    }
}