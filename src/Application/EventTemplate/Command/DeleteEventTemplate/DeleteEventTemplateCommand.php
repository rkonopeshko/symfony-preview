<?php

declare(strict_types=1);

namespace App\Application\EventTemplate\Command\DeleteEventTemplate;

use App\Application\Command\CommandInterface;

class DeleteEventTemplateCommand implements CommandInterface
{
    public function __construct(
        public int $id,
        public string $userId
    ) {

    }
}
