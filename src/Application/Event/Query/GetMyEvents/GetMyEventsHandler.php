<?php

declare(strict_types=1);

namespace App\Application\Event\Query\GetMyEvents;

use App\Application\Interfaces\EventServiceInterface;
use App\Application\Query\QueryHandlerInterface;

readonly class GetMyEventsHandler implements QueryHandlerInterface
{
    public function __construct(private EventServiceInterface $eventService)
    {

    }
    public function __invoke(GetMyEventsQuery $query): mixed
    {
        return $this->eventService->getUserEvents();
    }
}
