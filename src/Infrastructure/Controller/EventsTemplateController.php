<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Command\CommandBusInterface;
use App\Application\DTO\GoogleEventTemplate\CreateGoogleEventTemplateDto;
use App\Application\DTO\GoogleEventTemplate\ReplacePlaceholdersDto;
use App\Application\DTO\GoogleEventTemplate\UpdateGoogleEventTemplateDto;
use App\Application\DTO\Pagination\PaginationRequestDto;
use App\Application\EventTemplate\Command\CreateEventTemplate\CreateEventTemplateCommand;
use App\Application\EventTemplate\Command\DeleteEventTemplate\DeleteEventTemplateCommand;
use App\Application\EventTemplate\Command\UpdateEventTemplate\UpdateEventTemplateCommand;
use App\Application\EventTemplate\Query\GetEventTemplateById\GetEventTemplateByIdQuery;
use App\Application\EventTemplate\Query\GetUserEventTemplates\GetUserEventTemplatesQuery;
use App\Application\Query\QueryBusInterface;
use App\Domain\Exception\EventTemplate\EventTemplateNotFoundException;
use App\Domain\Exception\User\NotAllowedToUpdateEventTemplateException;
use Exception;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationServiceException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[Route('/api/events', name: 'events')]
#[OA\Tag(name: 'EventsTemplateController')]
#[Security(name: "Bearer")]
#[Assert\Callback]
class EventsTemplateController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    protected function getUser(): UserInterface
    {
        if (!$this->container->has('security.token_storage')) {
            throw new \LogicException('The SecurityBundle is not registered in your application. Try running "composer require symfony/security-bundle".');
        }

        if (null === $token = $this->container->get('security.token_storage')->getToken()) {
            throw new AuthenticationServiceException('No authentication token found');
        }

        return $token->getUser();
    }

    #[Route('/event-template', methods: ['POST'], format: 'json')]
    public function createEventTemlplate(#[MapRequestPayload] CreateGoogleEventTemplateDto $dto): JsonResponse
    {

        $this->commandBus->execute(new CreateEventTemplateCommand(
            $dto,
            $this->getUser()->getUserIdentifier(),
        ));
        return new JsonResponse(null, Response::HTTP_CREATED);
    }

    #[Route('/event-template', methods: ['PUT'], format: 'json')]
    public function updateEventTemplate(#[MapRequestPayload] UpdateGoogleEventTemplateDto $dto): JsonResponse
    {
        try {
            $this->commandBus->execute(
                new UpdateEventTemplateCommand(
                    $dto,
                    $this->getUser()->getUserIdentifier(),
                ),
            );
        } catch (EventTemplateNotFoundException $e) {
            return $this->json(
                ['success' => false, 'message' => $e->getMessage()],
                Response::HTTP_NOT_FOUND,
            );
        } catch (NotAllowedToUpdateEventTemplateException $e) {
            return $this->json(
                ['success' => false, 'message' => $e->getMessage()],
                Response::HTTP_FORBIDDEN,
            );
        } catch (Exception $e) {
            return $this->json(
                ['success' => false, 'message' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST,
            );
        }
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/event-template', methods: ['DELETE'], format: 'json')]
    public function deleteEventTemplate(#[MapQueryParameter] int $id): JsonResponse
    {
        try {
            $this->commandBus->execute(
                new DeleteEventTemplateCommand(
                    $id,
                    $this->getUser()->getUserIdentifier(),
                ),
            );
        } catch (EventTemplateNotFoundException $e) {
            return $this->json(
                ['success' => false, 'message' => $e->getMessage()],
                Response::HTTP_NOT_FOUND,
            );
        } catch (Exception $e) {
            return $this->json(
                ['success' => false, 'message' => $e->getMessage()],
                Response::HTTP_FORBIDDEN,
            );
        }

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/user-event-templates', methods: ['GET'], format: 'json')]
    public function getUserEventTemplates(#[MapQueryString] PaginationRequestDto $request): JsonResponse
    {
        $userId = $this->getUser()->getUserIdentifier();
        return $this->json(
            [
                'data' => $this->queryBus->execute(
                    new GetUserEventTemplatesQuery(
                        $userId,
                        $request,
                    ),
                ),
            ],
        );
    }

    #[Route('/event-template/{id}', methods: ['GET'], format: 'json')]
    public function getEventTemplateById(int $id): JsonResponse
    {
        try {
            $template = $this->json(
                $this->queryBus->execute(
                    new GetEventTemplateByIdQuery($id),
                ),
            );
        } catch (EventTemplateNotFoundException $e) {
            return $this->json(
                ['success' => false, 'message' => $e->getMessage()],
                Response::HTTP_NOT_FOUND,
            );
        }
        return $template;

    }

    #[Route('/event-template-placeholders', methods: ['POST'], format: 'json')]
    public function replacePlaceholders(#[MapRequestPayload] ReplacePlaceholdersDto $placeholders): JsonResponse
    {
        try {
            $response = $this->queryBus->execute(new GetEventTemplateByIdQuery($placeholders->id));
        } catch (EventTemplateNotFoundException $e) {
            return $this->json(
                ['success' => false, 'message' => $e->getMessage()],
                Response::HTTP_NOT_FOUND,
            );
        }

        return $this->json([
            'data' => $response]);

    }
}
