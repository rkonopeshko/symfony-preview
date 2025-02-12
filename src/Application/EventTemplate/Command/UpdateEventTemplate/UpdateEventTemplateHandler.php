<?php

declare(strict_types=1);

namespace App\Application\EventTemplate\Command\UpdateEventTemplate;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Entity\GoogleEventTemplateAttendee;
use App\Domain\Entity\GoogleEventTemplateReminder;
use App\Domain\Enum\GoogleEventTemplateAttendee\AttendeeTypeEnum;
use App\Domain\Enum\GoogleEventTemplateReminder\ReminderMethodTypeEnum;
use App\Domain\Exception\EventTemplate\EventTemplateNotFoundException;
use App\Domain\Exception\User\NotAllowedToUpdateEventTemplateException;
use App\Domain\ValueObject\GoogleEventTemplate\DescriptionValueObject;
use App\Domain\ValueObject\GoogleEventTemplate\DurationValueObject;
use App\Domain\ValueObject\GoogleEventTemplate\LocationValueObject;
use App\Domain\ValueObject\GoogleEventTemplate\TitleValueObject;
use App\Domain\ValueObject\GoogleEventTemplateAttendee\EmailValueObject;
use App\Domain\ValueObject\GoogleEventTemplateReminder\MinutesValueObject;
use App\Infrastructure\Repository\GoogleEventTemplateRepository;
use Doctrine\Common\Collections\ArrayCollection;

readonly class UpdateEventTemplateHandler implements CommandHandlerInterface
{
    public function __construct(
        private GoogleEventTemplateRepository $googleEventTemplateRepository,
    ) {
    }

    /**
     * @throws EventTemplateNotFoundException
     * @throws NotAllowedToUpdateEventTemplateException
     */
    public function __invoke(UpdateEventTemplateCommand $command): void
    {
        $event = $this->googleEventTemplateRepository->findById($command->dto->id);
        $event->validateCanBeUpdated($command->userId);
        $reminders = new ArrayCollection();
        $attendees = new ArrayCollection();
        foreach ($command->dto->reminders as $item) {
            $reminder = new GoogleEventTemplateReminder(
                ReminderMethodTypeEnum::from($item->method),
                MinutesValueObject::fromValue($item->minutes),
                $event,
            );
            $reminders->add($reminder);
        }
        foreach ($command->dto->attendees as $email) {
            $attendee = new GoogleEventTemplateAttendee(
                EmailValueObject::fromValue($email),
                $event,
                AttendeeTypeEnum::PERSON,
            );
            $attendees->add($attendee);
        }

        foreach ($command->dto->groupAttendees as $email) {
            $attendee = new GoogleEventTemplateAttendee(
                EmailValueObject::fromValue($email),
                $event,
                AttendeeTypeEnum::GROUP,
            );
            $attendees->add($attendee);
        }
        $event->update(
            TitleValueObject::fromValue($command->dto->title),
            DescriptionValueObject::fromValue($command->dto->description),
            LocationValueObject::fromValue($command->dto->location),
            DurationValueObject::fromValue($command->dto->duration),
            $reminders,
            $attendees,
            $command->dto->conference,
        );
        $this->googleEventTemplateRepository->update($event);
    }
}
