<?php

declare(strict_types=1);

namespace App\Application\EventTemplate\Query\GetUserEventTemplates;

use App\Application\DTO\Pagination\PaginationResponseDto;
use App\Application\Factory\GoogleEventTemplate\GetGoogleEventTemplateFactory;
use App\Application\Factory\Pagination\PaginationResponseFactory;
use App\Application\Query\QueryHandlerInterface;
use App\Domain\ValueObject\GoogleEventTemplate\CreatorIdValueObject;
use App\Infrastructure\Repository\GoogleEventTemplateRepository;

readonly class GetUserEventsTemplateHandler implements QueryHandlerInterface
{
    public function __construct(
        private GoogleEventTemplateRepository $googleEventTemplateRepository,
        private GetGoogleEventTemplateFactory $googleEventTemplateFactory,
        private PaginationResponseFactory $paginationResponseFactory,
    ) {
    }

    /**
     * @param GetUserEventTemplatesQuery $query
     * @return PaginationResponseDto
     */
    public function __invoke(GetUserEventTemplatesQuery $query): PaginationResponseDto
    {
        $creatorId = CreatorIdValueObject::fromValue($query->userId);

        $templates = $this->googleEventTemplateRepository
            ->findBy(
                ['creatorId' => $creatorId],
                orderBy: ['id' => 'DESC'],
                limit: $query->pagination->limit,
                offset: ($query->pagination->page - 1) * $query->pagination->limit,
            );

        $total = $this->googleEventTemplateRepository->count(['creatorId' => $creatorId]);

        $templateDtos = array_map(
            [$this->googleEventTemplateFactory, 'createFromEntity'],
            $templates
        );

        return $this->paginationResponseFactory->create(
            $query->pagination->page,
            $query->pagination->limit,
            $total,
            $templateDtos
        );
    }
}

