<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\TokenExchanger;

interface TokenExchangerInterface
{
    public function exchangeToken(string $accessToken): string;
}