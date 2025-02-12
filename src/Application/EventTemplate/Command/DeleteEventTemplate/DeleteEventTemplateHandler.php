<?php

declare(strict_types=1);

namespace App\Application\EventTemplate\Command\DeleteEventTemplate;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Exception\EventTemplate\EventTemplateNotFoundException;
use App\Domain\Exception\User\NotAllowedToUpdateEventTemplateException;
use App\Infrastructure\Repository\GoogleEventTemplateRepository;

readonly class DeleteEventTemplateHandler implements CommandHandlerInterface
{
    public function __construct(
        private GoogleEventTemplateRepository $googleEventTemplateRepository
    ) {
    }

    /**
     * @throws EventTemplateNotFoundException
     * @throws NotAllowedToUpdateEventTemplateException
     */
    public function __invoke(DeleteEventTemplateCommand $command): void
    {
        $event = $this->googleEventTemplateRepository->findById($command->id);
        $event->validateCanBeUpdated($command->userId);
        $this->googleEventTemplateRepository->delete($command->id);
    }
}
