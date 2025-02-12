<?php

declare(strict_types=1);

namespace App\Application\DTO\GoogleEventTemplate;

use App\Application\DTO\GoogleEvent\ReminderDto;

class GetGoogleEventTemplateDto
{
    /**
     * @param int|null $id
     * @param string|null $title
     * @param string|null $description
     * @param string|null $duration
     * @param string|null $location
     * @param bool|null $conference
     * @param string[]|null $attendees
     * @param string[]|null $groupAttendees
     * @param ReminderDto[]|null $reminders
     */
    public function __construct(
        public ?int $id,
        public ?string $title,
        public ?string $description,
        public ?string $duration,
        public ?string $location,
        public ?bool $conference,
        public ?array $attendees,
        public ?array $groupAttendees,
        public ?array $reminders
    ) {
    }
}
