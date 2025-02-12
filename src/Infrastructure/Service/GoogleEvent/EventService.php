<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\GoogleEvent;

use App\Application\DTO\FreeBusy\FreeBusyRequestDto;
use App\Application\DTO\GoogleEvent\CreateGoogleEventDto;
use App\Application\Interfaces\EventServiceInterface;
use App\Infrastructure\Service\Provider\CalendarProviderInterface;
use DateTime;
use DateTimeZone;
use Google\Service\Calendar\FreeBusyRequest;
use Google_Service_Calendar_ConferenceData;
use Google_Service_Calendar_CreateConferenceRequest;
use Google_Service_Calendar_Event;

class EventService implements EventServiceInterface
{
    public function __construct(
        private CalendarProviderInterface $calendarProvider
    ) {}

    public function createEvent(CreateGoogleEventDto $dto, string $organizer): void
    {
        $calendarService = $this->calendarProvider->getCalendar();
        $timeZone = $calendarService->calendars->get('primary')->getTimeZone();

        $event = new Google_Service_Calendar_Event([
            'summary' => $dto->title,
            'location' => $dto->location,
            'description' => $dto->description,
            'start' => ['dateTime' => $this->formatDateTime($dto->startDateTime, $timeZone)],
            'end' => ['dateTime' => $this->formatDateTime($dto->endDateTime, $timeZone)],
            'attendees' => $this->buildAttendeesArray($dto->attendees, $organizer),
            'reminders' => $this->buildRemindersArray($dto->reminders),
            'conferenceData' => $dto->conference ? $this->buildConferenceData() : null,
        ]);

        $calendarService->events->insert('primary', $event, ['conferenceDataVersion' => 1]);
    }

    public function getUserEvents(): array
    {
        $calendarService = $this->calendarProvider->getCalendar();
        $events = $calendarService->events->listEvents('primary', [
            'singleEvents' => true,
            'orderBy' => 'startTime',
        ])->getItems();

        return array_map(fn($event) => sprintf(
            '%s (%s - %s)',
            $event->getSummary(),
            $event->getStart()->getDateTime() ?? '',
            $event->getEnd()->getDateTime() ?? ''
        ), $events);
    }

    private function formatDateTime(DateTime $dateTime, string $timeZone): string
    {
        return $dateTime->setTimezone(new DateTimeZone($timeZone))->format(DATE_RFC3339);
    }

    private function buildAttendeesArray(array $attendees, string $organizer): array
    {
        return array_merge(
            array_map(fn($attendee) => ['email' => $attendee->email], $attendees),
            [['email' => $organizer]]
        );
    }

    private function buildRemindersArray(array $reminders): array
    {
        return [
            'useDefault' => false,
            'overrides' => array_map(
                fn($reminder) => ['method' => $reminder->method, 'minutes' => $reminder->minutes],
                $reminders
            ),
        ];
    }

    private function buildConferenceData(): Google_Service_Calendar_ConferenceData
    {
        $conferenceData = new Google_Service_Calendar_ConferenceData();
        $createRequest = new Google_Service_Calendar_CreateConferenceRequest();
        $createRequest->setRequestId(uniqid());
        $conferenceData->setCreateRequest($createRequest);

        return $conferenceData;
    }
}