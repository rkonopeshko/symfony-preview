<?php

declare(strict_types=1);

namespace App\Application\Event\Command\PublishEvent;

use App\Application\Command\CommandHandlerInterface;
use App\Application\Factory\GoogleEvent\GoogleEventDtoFactory;
use App\Application\Interfaces\EventServiceInterface;
use App\Application\Service\Placeholders\PlaceholderReplacerInterface;
use App\Domain\Entity\GoogleEventTemplateAttendee;
use App\Domain\Exception\EventTemplate\EventTemplateNotFoundException;
use App\Domain\Repository\GoogleEventTemplateRepositoryInterface;

readonly class PublishEventHandler implements CommandHandlerInterface
{
    public function __construct(
        private EventServiceInterface $eventService,
        private GoogleEventTemplateRepositoryInterface $googleEventTemplateRepository,
        private PlaceholderReplacerInterface $placeholderReplacer,
        private GoogleEventDtoFactory $googleEventDtoFactory
    ) {

    }

    /**
     * @throws EventTemplateNotFoundException
     */
    public function __invoke(PublishEventCommand $command): void
    {
        $googleEventTemplate = $this->googleEventTemplateRepository->findById($command->request->eventTemplateId);

        $replacedTitle = $this->placeholderReplacer->replace(
            (string)$googleEventTemplate->getTitle(),
            $command->request->titlePlaceholders
        );

        $replacedDescription = $this->placeholderReplacer->replace(
            (string)$googleEventTemplate->getDescription(),
            $command->request->descriptionPlaceholders
        );

        $attendees = array_merge(
            $command->request->attendees,
            $command->request->groupAttendees,
            array_map(
                fn (GoogleEventTemplateAttendee $attendee) =>
                    (string)$attendee->getEmail(),
                $googleEventTemplate->getAttendees()->toArray(),
            ),
        );
        $createEventDto = $this->googleEventDtoFactory->create(
            $replacedTitle,
            $replacedDescription,
            $command->request->start,
            $googleEventTemplate->duration->toValue(),
            (string)$googleEventTemplate->getLocation(),
            $googleEventTemplate->isConference() ?? false,
            $attendees,
            $googleEventTemplate->getReminders()->toArray()
        );

        $this->eventService->createEvent($createEventDto, $command->organizer);
    }
}
