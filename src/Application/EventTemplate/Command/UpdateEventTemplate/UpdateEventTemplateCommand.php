<?php

declare(strict_types=1);

namespace App\Application\EventTemplate\Command\UpdateEventTemplate;

use App\Application\Command\CommandInterface;
use App\Application\DTO\GoogleEventTemplate\UpdateGoogleEventTemplateDto;

class UpdateEventTemplateCommand implements CommandInterface
{
    public function __construct(
        public UpdateGoogleEventTemplateDto $dto,
        public string $userId
    ) {
    }
}
