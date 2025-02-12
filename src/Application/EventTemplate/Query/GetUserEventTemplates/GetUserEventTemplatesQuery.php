<?php

declare(strict_types=1);

namespace App\Application\EventTemplate\Query\GetUserEventTemplates;

use App\Application\DTO\Pagination\PaginationRequestDto;
use App\Application\Query\QueryInterface;

class GetUserEventTemplatesQuery implements QueryInterface
{
    public function __construct(
        public string $userId,
        public PaginationRequestDto $pagination
    ) {
    }
}
