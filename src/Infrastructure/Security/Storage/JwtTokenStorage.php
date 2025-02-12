<?php

declare(strict_types=1);

namespace App\Infrastructure\Security\Storage;

class JwtTokenStorage
{
    private string $token;
    public function getToken(): string
    {
        return $this->token;
    }
    public function setToken(string $token): void
    {
        $this->token = $token;
    }
}
