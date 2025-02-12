<?php

declare(strict_types=1);

namespace App\Application\DTO\GoogleEventTemplate;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateGoogleEventTemplateDto
{
    /**
     * @param int $id
     * @param string $title
     * @param string $description
     * @param string $location
     * @param int $duration
     * @param bool $conference
     * @param string[] $attendees
     * @param string[] $groupAttendees
     * @param UpdateReminderDto[] $reminders
     */
    public function __construct(
        public int $id,
        public string $title,
        public string $description,
        public string $location,
        #[Assert\GreaterThan(0)]
        public int $duration,
        public bool $conference,
        #[Assert\All([
            new Assert\Email(),
        ])]
        public array $attendees,
        #[Assert\All([
            new Assert\Email(),
        ])]
        public array $groupAttendees,
        #[Assert\Valid]
        public array $reminders,
    ) {
    }
}
