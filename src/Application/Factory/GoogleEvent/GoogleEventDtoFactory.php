<?php

declare(strict_types=1);

namespace App\Application\Factory\GoogleEvent;

use App\Application\DTO\GoogleEvent\AttendeeDto;
use App\Application\DTO\GoogleEvent\CreateGoogleEventDto;
use App\Application\DTO\GoogleEvent\ReminderDto;
use App\Domain\Entity\GoogleEventTemplateReminder;
use DateInterval;
use DateTime;
use DateTimeInterface;

class GoogleEventDtoFactory
{
    public function create(
        string $title,
        string $description,
        DateTimeInterface $start,
        int $duration,
        string $location,
        bool $conference,
        array $attendees,
        array $reminders,
    ): CreateGoogleEventDto {
        $endDateTime = new DateTime($start->format('Y-m-d H:i:s'));
        $endDateTime->add(new DateInterval('PT' . $duration . 'M'));

        return new CreateGoogleEventDto(
            $title,
            $description,
            $start,
            $endDateTime,
            $location,
            $conference,
            array_map(
                fn (string $attendee) => new AttendeeDto($attendee),
                $attendees,
            ),
            array_map(
                fn (GoogleEventTemplateReminder $reminder) => new ReminderDto(
                    $reminder->getMethod(),
                    (int)(string)$reminder->getMinutes(),
                ),
                $reminders,
            ),
        );
    }
}
