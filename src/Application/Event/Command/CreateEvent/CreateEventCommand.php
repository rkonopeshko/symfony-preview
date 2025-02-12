<?php

declare(strict_types=1);

namespace App\Application\Event\Command\CreateEvent;

use App\Application\Command\CommandInterface;
use App\Application\DTO\GoogleEvent\CreateGoogleEventDto;

class CreateEventCommand implements CommandInterface
{
    public function __construct(
        public CreateGoogleEventDto $dto,
        public string $organizer
    ) {
    }
}
