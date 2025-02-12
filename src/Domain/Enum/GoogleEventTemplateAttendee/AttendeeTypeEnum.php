<?php

declare(strict_types=1);

namespace App\Domain\Enum\GoogleEventTemplateAttendee;

enum AttendeeTypeEnum : string
{
    case PERSON = 'person';
    case GROUP = 'group';
}
