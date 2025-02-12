<?php

declare(strict_types=1);

namespace App\Application\DTO\GoogleEvent;
use App\Domain\Enum\GoogleEventTemplateReminder\ReminderMethodTypeEnum;
use Symfony\Component\Validator\Constraints as Assert;

class ReminderDto
{
    public function __construct(
        public ReminderMethodTypeEnum $method,
        #[Assert\Positive]
        public int $minutes,
    ) {
    }
}
