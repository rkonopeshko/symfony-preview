<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\TokenExchanger;

use App\Infrastructure\Exception\TokenExchanger\FailedExchangeTokenException;
use App\Infrastructure\Exception\TokenExchanger\TokenNotFoundInExchangerException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

readonly class TokenExchanger implements TokenExchangerInterface
{
    public function __construct(
        private Client $client,
        private string $keycloakUrl,
        private string $clientId,
        private string $clientSecret
    ) {
    }

    /**
     * @throws TokenNotFoundInExchangerException
     * @throws FailedExchangeTokenException
     * @throws GuzzleException
     */
    public function exchangeToken(string $accessToken): string
    {
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json',
        ];

        $requestBody = [
            'form_params' => [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type' => 'urn:ietf:params:oauth:grant-type:token-exchange',
                'subject_token_type' => 'urn:ietf:params:oauth:token-type:access_token',
                'subject_token' => $accessToken,
                'requested_issuer' => 'google',
            ],
        ];

        try {
            $response = $this->client->post($this->keycloakUrl, [
                'headers' => $headers,
                'form_params' => $requestBody['form_params'],
            ]);

            $responseBody = json_decode(
                $response->getBody()->getContents(),
                true
            );

            if (isset($responseBody['access_token'])) {
                return $responseBody['access_token'];
            }

            throw new TokenNotFoundInExchangerException(
                'Access token not found in the response.'
            );
        } catch (RequestException $e) {
            throw new FailedExchangeTokenException(
                'Failed to exchange token: ' . $e->getMessage()
            );
        }
    }
}
