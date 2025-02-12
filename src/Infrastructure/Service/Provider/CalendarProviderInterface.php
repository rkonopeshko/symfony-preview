<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Provider;

use Google\Service\Calendar;

interface CalendarProviderInterface
{
    public function getCalendar(): Calendar;
}