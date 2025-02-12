<?php

declare(strict_types=1);

namespace App\Application\Factory\GoogleEventTemplate;

use App\Application\DTO\GoogleEvent\ReminderDto;
use App\Application\DTO\GoogleEventTemplate\GetGoogleEventTemplateDto;
use App\Domain\Entity\GoogleEventTemplate;
use App\Domain\Entity\GoogleEventTemplateAttendee;
use App\Domain\Entity\GoogleEventTemplateReminder;
use App\Domain\Enum\GoogleEventTemplateAttendee\AttendeeTypeEnum;

class GetGoogleEventTemplateFactory
{
    /**
     * @param GoogleEventTemplate $template
     * @return GetGoogleEventTemplateDto
     */
    public function createFromEntity(GoogleEventTemplate $template): GetGoogleEventTemplateDto
    {
        return new GetGoogleEventTemplateDto(
            $template->getId(),
            (string)$template->getTitle(),
            (string)$template->getDescription(),
            (string)$template->getDuration(),
            (string)$template->getLocation(),
            $template->isConference(),
            $this->extractEmails($template->getAttendees()->toArray(), AttendeeTypeEnum::PERSON),
            $this->extractEmails($template->getAttendees()->toArray(), AttendeeTypeEnum::GROUP),
            $this->extractReminders($template->getReminders()->toArray())
        );
    }

    /**
     * @param GoogleEventTemplateAttendee[] $attendees
     * @param AttendeeTypeEnum $type
     * @return array<string>
     */
    private function extractEmails(array $attendees, AttendeeTypeEnum $type): array
    {
        return array_values(array_map(
            fn (GoogleEventTemplateAttendee $attendee) => (string)$attendee->getEmail(),
            array_filter(
                $attendees,
                fn (GoogleEventTemplateAttendee $attendee) => $attendee->getAttendeeType() === $type
            )
        ));
    }

    /**
     * @param GoogleEventTemplateReminder[] $reminders
     * @return ReminderDto[]
     */
    private function extractReminders(array $reminders): array
    {
        return array_map(
            fn (GoogleEventTemplateReminder $reminder) => new ReminderDto(
                $reminder->getMethod(),
                $reminder->getMinutes()->toValue()
            ),
            $reminders
        );
    }
}
