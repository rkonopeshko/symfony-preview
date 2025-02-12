<?php

declare(strict_types=1);

namespace App\Application\DTO\FreeBusy;

use DateTime;

class FreeBusyRequestDto
{
    public DateTime $timeMin;
    public DateTime $timeMax;
}
