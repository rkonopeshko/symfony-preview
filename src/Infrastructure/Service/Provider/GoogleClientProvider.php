<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Provider;

use App\Infrastructure\Exception\Google\GoogleAuthConfigException;
use App\Infrastructure\Security\Storage\JwtTokenStorage;
use App\Infrastructure\Service\TokenExchanger\TokenExchangerInterface;
use Google\Client;
use Google\Exception;
use Google\Service\Calendar;

readonly class GoogleClientProvider implements GoogleClientProviderInterface
{
    public function __construct(
        private Client $client,
        private TokenExchangerInterface $tokenExchanger,
        private JwtTokenStorage $tokenStorage,
        private string $authConfigPath,
    ) {
    }

    /**
     * @throws GoogleAuthConfigException
     */
    public function getClient(): Client
    {
        $token = $this->tokenStorage->getToken();
        $googleToken = $this->tokenExchanger->exchangeToken($token);
        try {
            $this->client->setAuthConfig($this->authConfigPath);
        } catch (Exception $e) {
            throw new GoogleAuthConfigException($e->getMessage());
        }
        $this->client->addScope(Calendar::CALENDAR);
        $this->client->setAccessToken($googleToken);

        return $this->client;
    }
}
