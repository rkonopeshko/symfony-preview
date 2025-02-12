<?php

declare(strict_types=1);

namespace App\Application\EventTemplate\Command\CreateEventTemplate;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Entity\GoogleEventTemplate;
use App\Domain\Entity\GoogleEventTemplateAttendee;
use App\Domain\Entity\GoogleEventTemplateReminder;
use App\Domain\Enum\GoogleEventTemplateAttendee\AttendeeTypeEnum;
use App\Domain\Enum\GoogleEventTemplateReminder\ReminderMethodTypeEnum;
use App\Domain\ValueObject\GoogleEventTemplate\CreatorIdValueObject;
use App\Domain\ValueObject\GoogleEventTemplate\DescriptionValueObject;
use App\Domain\ValueObject\GoogleEventTemplate\DurationValueObject;
use App\Domain\ValueObject\GoogleEventTemplate\LocationValueObject;
use App\Domain\ValueObject\GoogleEventTemplate\TitleValueObject;
use App\Domain\ValueObject\GoogleEventTemplateAttendee\EmailValueObject;
use App\Domain\ValueObject\GoogleEventTemplateReminder\MinutesValueObject;
use App\Infrastructure\Repository\GoogleEventTemplateAttendeeRepository;
use App\Infrastructure\Repository\GoogleEventTemplateReminderRepository;
use App\Infrastructure\Repository\GoogleEventTemplateRepository;

readonly class CreateEventTemplateHandler implements CommandHandlerInterface
{
    public function __construct(
        private GoogleEventTemplateRepository $googleEventTemplateRepository,
        private GoogleEventTemplateReminderRepository $reminderRepository,
        private GoogleEventTemplateAttendeeRepository $attendeeRepository,
    ) {
    }

    public function __invoke(CreateEventTemplateCommand $command): void
    {
        $googleEventTemplate = new GoogleEventTemplate(
            CreatorIdValueObject::fromValue($command->userId),
            TitleValueObject::fromValue($command->dto->title),
            DescriptionValueObject::fromValue($command->dto->description),
            LocationValueObject::fromValue($command->dto->location),
            DurationValueObject::fromValue($command->dto->duration),
            $command->dto->conference,
        );
        $this->googleEventTemplateRepository->create($googleEventTemplate);
        foreach ($command->dto->reminders as $item) {
            $reminder = new GoogleEventTemplateReminder(
                ReminderMethodTypeEnum::from($item->method),
                MinutesValueObject::fromValue($item->minutes),
                $googleEventTemplate
            );
            $this->reminderRepository->create($reminder);
        }
        foreach ($command->dto->attendees as $email) {
            $attendee = new GoogleEventTemplateAttendee(
                EmailValueObject::fromValue($email),
                $googleEventTemplate,
                AttendeeTypeEnum::PERSON
            );
            $this->attendeeRepository->create($attendee);
        }
        foreach ($command->dto->groupAttendees as $email) {
            $attendee = new GoogleEventTemplateAttendee(
                EmailValueObject::fromValue($email),
                $googleEventTemplate,
                AttendeeTypeEnum::GROUP
            );
            $this->attendeeRepository->create($attendee);
        }
    }
}
