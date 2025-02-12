<?php

declare(strict_types=1);

namespace App\Domain\Enum\GoogleEventTemplateReminder;

enum ReminderMethodTypeEnum: string
{
    case EMAIL = 'email';
    case POPUP = 'popup';
}
