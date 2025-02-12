<?php

declare(strict_types=1);

namespace App\Application\EventTemplate\Query\GetEventTemplateById;

use App\Application\DTO\GoogleEventTemplate\GetGoogleEventTemplateDto;
use App\Application\Factory\GoogleEventTemplate\GetGoogleEventTemplateFactory;
use App\Application\Query\QueryHandlerInterface;
use App\Domain\Exception\EventTemplate\EventTemplateNotFoundException;
use App\Domain\Repository\GoogleEventTemplateRepositoryInterface;

readonly class GetEventTemplateByIdQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private GoogleEventTemplateRepositoryInterface $googleEventTemplateRepository,
        private GetGoogleEventTemplateFactory $googleEventTemplateFactory,

    ) {
    }

    /**
     * @throws EventTemplateNotFoundException
     */
    public function __invoke(GetEventTemplateByIdQuery $query): GetGoogleEventTemplateDto
    {
        $template = $this->googleEventTemplateRepository->findById($query->id);

        return $this->googleEventTemplateFactory->createFromEntity($template);
    }
}
