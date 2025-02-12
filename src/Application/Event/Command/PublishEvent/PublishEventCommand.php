<?php

declare(strict_types=1);

namespace App\Application\Event\Command\PublishEvent;

use App\Application\Command\CommandInterface;
use App\Application\DTO\GoogleEvent\PublishEventRequestDto;

class PublishEventCommand implements CommandInterface
{
    public function __construct(
        public PublishEventRequestDto $request,
        public string $organizer
    ) {

    }
}
