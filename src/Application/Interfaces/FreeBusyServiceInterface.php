<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

use App\Application\DTO\FreeBusy\FreeBusyDto;
use App\Application\DTO\FreeBusy\FreeBusyRequestDto;

interface FreeBusyServiceInterface
{
    public function getUserFreeBusy(FreeBusyRequestDto $dto): FreeBusyDto;
}