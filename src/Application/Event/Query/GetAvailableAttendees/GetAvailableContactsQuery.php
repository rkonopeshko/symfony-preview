<?php

declare(strict_types=1);

namespace App\Application\Event\Query\GetAvailableAttendees;

use App\Application\Query\QueryInterface;

class GetAvailableContactsQuery implements QueryInterface
{
    public function __construct(
        public string $userId,
    ) {
    }
}