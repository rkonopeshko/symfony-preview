<?php

declare(strict_types=1);

namespace App\Application\EventTemplate\Command\ReplacePlaceholdersInEventTemplate;

use App\Application\Command\CommandHandlerInterface;
use App\Application\DTO\GoogleEventTemplate\GetGoogleEventTemplateDto;
use App\Domain\Exception\EventTemplate\EventTemplateNotFoundException;
use App\Infrastructure\Repository\GoogleEventTemplateRepository;

readonly class ReplacePlaceholdersInEventTemplateHandler implements CommandHandlerInterface
{
    public function __construct(
        private GoogleEventTemplateRepository $googleEventTemplateRepository,
    ) {
    }

    /**
     * @throws EventTemplateNotFoundException
     */
    public function __invoke(ReplacePlaceholdersInEventTemplateCommand $command): GetGoogleEventTemplateDto
    {
        $googleEventTemplate = $this->googleEventTemplateRepository->findById($command->replacePlaceholdersDto->id);

        $replacedTitle = $this->replacePlaceholders(
            $googleEventTemplate->getTitle()->toValue() ?? '',
            $command->replacePlaceholdersDto->placeholders,
        );
        $replacedDescription = $this->replacePlaceholders(
            $googleEventTemplate->getDescription()->toValue() ?? '',
            $command->replacePlaceholdersDto->placeholders,
        );

        return new GetGoogleEventTemplateDto(
            $googleEventTemplate->getId(),
            $replacedTitle,
            $replacedDescription,
            $googleEventTemplate->getStartDateTime()->toValue() ?? '',
            $googleEventTemplate->getEndDateTime()->toValue() ?? '',
            $googleEventTemplate->getLocation(),
            $googleEventTemplate->isConference(),
        );
    }

    /**
     * @param string $template
     * @param array<string> $placeholders
     * @return string
     */
    private function replacePlaceholders(string $template, array $placeholders): string
    {
        foreach ($placeholders as $key => $value) {
            $template = preg_replace('/\{'.++$key.'\}/', $value, $template);
        }
        return $template;
    }
}
