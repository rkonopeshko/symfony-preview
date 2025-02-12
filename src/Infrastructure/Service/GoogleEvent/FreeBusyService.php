<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\GoogleEvent;

use App\Application\DTO\FreeBusy\FreeBusyDto;
use App\Application\DTO\FreeBusy\FreeBusyRequestDto;
use App\Application\Interfaces\FreeBusyServiceInterface;
use App\Infrastructure\Service\Provider\CalendarProviderInterface;
use Google\Service\Calendar\FreeBusyRequest;

class FreeBusyService implements FreeBusyServiceInterface
{
    public function __construct(private CalendarProviderInterface $calendarProvider)
    {

    }
    public function getUserFreeBusy(FreeBusyRequestDto $dto) : FreeBusyDto
    {
        $freeBusyRequest = new FreeBusyRequest();
        $freeBusyRequest->setTimeMin($dto->timeMin->format(DATE_ATOM));
        $freeBusyRequest->setTimeMax($dto->timeMax->format(DATE_ATOM));
        $freeBusyRequest->setItems([['id' => 'primary']]);

        $calendarService = $this->calendarProvider->getCalendar();
        $freeBusyResponse = $calendarService->freebusy->query($freeBusyRequest);

        $dto = new FreeBusyDto();
        foreach ($freeBusyResponse->getCalendars() as $key => $calendar) {
            $dto->calendars[$key] = $calendar->getBusy();
        }
        $dto->timeMax = $freeBusyResponse->getTimeMax();
        $dto->timeMin = $freeBusyResponse->getTimeMin();

        return $dto;
    }
}
