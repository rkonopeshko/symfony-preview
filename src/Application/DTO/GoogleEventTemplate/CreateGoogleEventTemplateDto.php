<?php

declare(strict_types=1);

namespace App\Application\DTO\GoogleEventTemplate;

use Symfony\Component\Validator\Constraints as Assert;
class CreateGoogleEventTemplateDto
{
    /**
     * @param string $title
     * @param string $description
     * @param string $location
     * @param int $duration
     * @param bool $conference
     * @param string[] $attendees
     * @param string[] $groupAttendees
     * @param CreateReminderDto[] $reminders
     */
    public function __construct(
        public string $title,
        public string $description,
        public string $location,
        #[Assert\GreaterThan(0)]
        public int $duration,
        public bool $conference,
        public array $attendees,
        public array $groupAttendees,
        #[Assert\Valid]
        public array $reminders
    ) {
    }
}
