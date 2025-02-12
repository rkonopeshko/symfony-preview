<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use App\Infrastructure\Security\Exceptions\KeycloakRequestException;
use App\Infrastructure\Security\Storage\JwtTokenStorage;
use App\Infrastructure\Security\User\User;
use Exception;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class KeycloakApiAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly JwtTokenStorage $tokenStorage,
        private readonly string $jwksUrl,
    ) {
    }

    public function supports(Request $request): ?bool
    {
        return true;
    }
    public function authenticate(Request $request): Passport
    {
        $authorizationHeader = $request->headers->get('Authorization');

        if (!$authorizationHeader || !str_starts_with($authorizationHeader, 'Bearer ')) {
            throw new AuthenticationException('No Bearer token found');
        }

        $token = substr($authorizationHeader, 7);
        $this->tokenStorage->setToken($token);
        try {
            $decodedToken = JWT::decode($token, JWK::parseKeySet($this->getJwks()), $headers);
        } catch (Exception $e) {
            throw new AuthenticationException($e->getMessage());
        }

        return new SelfValidatingPassport(
            new UserBadge($decodedToken->sub, function () use ($decodedToken) {
                return new User(
                    $decodedToken->sub,
                    $decodedToken->email,
                    $decodedToken->realm_access->roles
                );
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => $exception->getMessage(),
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @return array<mixed>
     * @throws KeycloakRequestException
     */
    private function getJwks(): array
    {
        try {
            $response = $this->httpClient->request('GET', $this->jwksUrl);

            if (200 !== $response->getStatusCode()) {
                throw new KeycloakRequestException('Failed to fetch JWKS: '.$response->getStatusCode());
            }

            $jwks = $response->toArray();

            if (!isset($jwks['keys'])) {
                throw new KeycloakRequestException('Invalid JWKS format');
            }

            return $jwks;
        } catch (ExceptionInterface $e) {
            throw new AuthenticationException('Could not retrieve JWKS: '.$e->getMessage());
        }
    }
}
