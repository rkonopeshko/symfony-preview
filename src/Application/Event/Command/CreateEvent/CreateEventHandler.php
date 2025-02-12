<?php

declare(strict_types=1);

namespace App\Application\Event\Command\CreateEvent;

use App\Application\Command\CommandHandlerInterface;
use App\Application\Interfaces\EventServiceInterface;

readonly class CreateEventHandler implements CommandHandlerInterface
{
    public function __construct(private EventServiceInterface $eventService)
    {

    }

    public function __invoke(CreateEventCommand $command): void
    {
        $this->eventService->createEvent($command->dto, $command->organizer);
    }
}

