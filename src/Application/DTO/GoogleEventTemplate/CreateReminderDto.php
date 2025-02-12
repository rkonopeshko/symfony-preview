<?php

declare(strict_types=1);

namespace App\Application\DTO\GoogleEventTemplate;

use App\Domain\Enum\GoogleEventTemplateReminder\ReminderMethodTypeEnum;
use Symfony\Component\Validator\Constraints as Assert;

class CreateReminderDto
{
    public function __construct(
        #[Assert\Choice(
            choices: ['email', 'popup'],
            message: 'Choose a valid reminder method.',
        )]
        public string $method,
        #[Assert\Positive]
        public int $minutes,
    ) {
    }
}
