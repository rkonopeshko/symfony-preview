<?php

declare(strict_types=1);

namespace App\Application\DTO\Pagination;

class PaginationResponseDto
{
    public int $page;
    public int $limit;
    public int $total;
    public int $totalPages;
    public mixed $data;
}
