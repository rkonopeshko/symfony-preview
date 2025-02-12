<?php

declare(strict_types=1);

namespace App\Application\EventTemplate\Command\ReplacePlaceholdersInEventTemplate;

use App\Application\Command\CommandInterface;
use App\Application\DTO\GoogleEventTemplate\ReplacePlaceholdersDto;

class ReplacePlaceholdersInEventTemplateCommand implements CommandInterface
{
    public function __construct(
        public ReplacePlaceholdersDto $replacePlaceholdersDto
    ) {
    }
}
