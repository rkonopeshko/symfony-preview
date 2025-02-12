<?php

declare(strict_types=1);

namespace App\Application\DTO\Pagination;

use Symfony\Component\Validator\Constraints as Assert;
class PaginationRequestDto
{
    #[Assert\Positive]
    public int $page = 1;

    #[Assert\Positive]
    public int $limit = 10;
}

