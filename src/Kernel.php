<?php

declare(strict_types=1);

namespace App;

use App\Domain\ValueObject\GoogleEventTemplate\AttendeesCollectionValueObject;
use App\Domain\ValueObject\GoogleEventTemplate\AttendeeValueObject;
use App\Domain\ValueObject\GoogleEventTemplate\CreatorIdValueObject;
use App\Domain\ValueObject\GoogleEventTemplate\DescriptionValueObject;
use App\Domain\ValueObject\GoogleEventTemplate\DurationValueObject;
use App\Domain\ValueObject\GoogleEventTemplate\EventTimeValueObject;
use App\Domain\ValueObject\GoogleEventTemplate\LocationValueObject;
use App\Domain\ValueObject\GoogleEventTemplate\ReminderValueObject;
use App\Domain\ValueObject\GoogleEventTemplate\TitleValueObject;
use App\Domain\ValueObject\GoogleEventTemplateAttendee\EmailValueObject;
use App\Domain\ValueObject\GoogleEventTemplateReminder\MethodValueObject;
use App\Domain\ValueObject\GoogleEventTemplateReminder\MinutesValueObject;
use Doctrine\DBAL\Types\Type;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Yokai\DoctrineValueObject\Doctrine\Types;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private const DOCTRINE_VALUE_OBJECTS = [
        'google_event_template_description' => DescriptionValueObject::class,
        'google_event_template_title' => TitleValueObject::class,
        'google_event_template_duration' => DurationValueObject::class,
        'google_event_template_location' => LocationValueObject::class,
        'google_event_template_creator_id' => CreatorIdValueObject::class,
        'google_event_template_reminder_method' => MethodValueObject::class,
        'google_event_template_reminder_minutes' => MinutesValueObject::class,
        'google_event_template_attendee_email' => EmailValueObject::class,
    ];

    public function __construct(string $environment, bool $debug)
    {
        parent::__construct($environment, $debug);
        (new Types(self::DOCTRINE_VALUE_OBJECTS))->register(Type::getTypeRegistry());
    }
}
