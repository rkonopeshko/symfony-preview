<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\GoogleEventTemplateReminder;

use Webmozart\Assert\Assert;
use Yokai\DoctrineValueObject\StringValueObject;

class MethodValueObject implements StringValueObject
{
    private const POPUP = 'popup';
    private const EMAIL = 'email';
    private const METHODS = [
        self::POPUP,
        self::EMAIL,
    ];
    private readonly string $method;

    public function __construct(string $method)
    {
        $this->method = $method;
    }
    public static function fromValue(string $value): static
    {
        Assert::oneOf($value, self::METHODS);
        return new static($value);
    }

    public function toValue(): string
    {
        return $this->method;
    }

    public function __toString(): string
    {
        return $this->method;
    }
}
