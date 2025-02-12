<?php

declare(strict_types=1);

namespace App\Application\Factory\Pagination;

use App\Application\DTO\Pagination\PaginationResponseDto;

class PaginationResponseFactory
{
    /**
     * @param int $page
     * @param int $limit
     * @param int $total
     * @param array<mixed> $data
     * @return PaginationResponseDto
     */
    public function create(int $page, int $limit, int $total, array $data): PaginationResponseDto
    {
        $response = new PaginationResponseDto();
        $response->total = $total;
        $response->page = $page;
        $response->limit = $limit;
        $response->totalPages = (int)ceil($total / $limit);
        $response->data = $data;

        return $response;
    }
}
