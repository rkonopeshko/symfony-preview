<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

use App\Application\DTO\GoogleEvent\CreateGoogleEventDto;

interface EventServiceInterface
{
    public function createEvent(
        CreateGoogleEventDto $dto,
        string $organizer,
    ): void;

    public function getUserEvents(): array;
}
