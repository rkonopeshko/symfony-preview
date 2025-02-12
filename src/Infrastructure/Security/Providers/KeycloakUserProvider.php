<?php

declare(strict_types=1);

namespace App\Infrastructure\Security\Providers;

use App\Infrastructure\Security\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class KeycloakUserProvider implements UserProviderInterface
{
    public function __construct(
    ) {
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return $class === User::class;
    }
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        throw new \Exception('TODO: fill in loadUserByIdentifier() inside '.__FILE__);
    }
}
