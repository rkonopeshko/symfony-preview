<?php

declare(strict_types=1);

namespace App\Infrastructure\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;

final readonly class User implements UserInterface
{
    /**
     * @param string $userIdentifier
     * @param string $email
     * @param array<string> $roles
     */
    public function __construct(
        private string $userIdentifier,
        private string $email,
        private array $roles,
    ) {
    }
    public function getRoles(): array
    {
        return $this->roles;
    }
    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->userIdentifier;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
