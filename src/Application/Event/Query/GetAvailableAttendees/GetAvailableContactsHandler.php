<?php

declare(strict_types=1);

namespace App\Application\Event\Query\GetAvailableAttendees;

use App\Application\Query\QueryHandlerInterface;
use App\Infrastructure\Service\Provider\CalendarProvider;

class GetAvailableContactsHandler implements QueryHandlerInterface
{
    public function __construct(private CalendarProvider $calendarProvider)
    {
    }

    public function __invoke(GetAvailableContactsQuery $query): array
    {
        // TODO: Implement the logic to fetch available contacts
        return [];
    }
}
