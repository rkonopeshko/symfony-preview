<?php

declare(strict_types=1);

namespace App\Application\Event\Query\GetFreebusy;

use App\Application\DTO\FreeBusy\FreeBusyRequestDto;
use App\Application\Query\QueryInterface;

class GetFreeBusyQuery implements QueryInterface
{
    public function __construct(
        public FreeBusyRequestDto $freeBusyRequestDto
    ) {
    }
}
