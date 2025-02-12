<?php

declare(strict_types=1);

namespace App\Application\DTO\FreeBusy;

use Google\Service\Calendar\TimePeriod;

class FreeBusyDto
{
    public string $timeMax;
    public string $timeMin;

    /**
     * @var TimePeriod[]
     */
    public array $calendars;
}
