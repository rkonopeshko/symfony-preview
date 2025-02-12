<?php

declare(strict_types=1);

namespace App\Application\DTO\GoogleEvent;

use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CreateGoogleEventDto
{
    public function __construct(
        public string $title,
        public string $description,
        public DateTimeInterface $startDateTime,
        public DateTimeInterface $endDateTime,
        public string $location,
        public bool $conference,
        #[Assert\Valid]
        /** @var AttendeeDto[] */
        public array $attendees = [],
        #[Assert\Valid]
        /** @var ReminderDto[] */
        public array $reminders = [],
    ) {
    }
}

