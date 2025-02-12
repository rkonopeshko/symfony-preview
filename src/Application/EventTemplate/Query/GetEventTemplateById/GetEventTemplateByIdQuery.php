<?php

declare(strict_types=1);

namespace App\Application\EventTemplate\Query\GetEventTemplateById;

use App\Application\Query\QueryInterface;

class GetEventTemplateByIdQuery implements QueryInterface
{
    public function __construct(
        public int $id,
    ) {
    }
}

