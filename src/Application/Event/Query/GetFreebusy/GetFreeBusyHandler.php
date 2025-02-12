<?php

declare(strict_types=1);

namespace App\Application\Event\Query\GetFreebusy;

use App\Application\DTO\FreeBusy\FreeBusyDto;
use App\Application\Interfaces\FreeBusyServiceInterface;
use App\Application\Query\QueryHandlerInterface;

readonly class GetFreeBusyHandler implements QueryHandlerInterface
{
    public function __construct(
        private FreeBusyServiceInterface $freeBusyService,
    ) {
    }

    public function __invoke(GetFreeBusyQuery $query): FreeBusyDto
    {
        return $this->freeBusyService->getUserFreeBusy($query->freeBusyRequestDto);
    }
}
