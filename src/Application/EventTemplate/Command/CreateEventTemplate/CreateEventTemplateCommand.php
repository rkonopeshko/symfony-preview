<?php

declare(strict_types=1);

namespace App\Application\EventTemplate\Command\CreateEventTemplate;

use App\Application\Command\CommandInterface;
use App\Application\DTO\GoogleEventTemplate\CreateGoogleEventTemplateDto;

class CreateEventTemplateCommand implements CommandInterface
{
    public function __construct(
        public CreateGoogleEventTemplateDto $dto,
        public string $userId
    ) {
    }
}
