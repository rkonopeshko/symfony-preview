<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Provider;

use Google\Service\Calendar;

readonly class CalendarProvider implements CalendarProviderInterface
{
    public function __construct(
        private GoogleClientProviderInterface $googleClientProvider,
    ) {
    }
    public function getCalendar(): Calendar
    {
        return new Calendar($this->googleClientProvider->getClient());
    }
}

