<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Command\CommandBusInterface;
use App\Application\DTO\FreeBusy\FreeBusyRequestDto;
use App\Application\DTO\GoogleEvent\CreateGoogleEventDto;
use App\Application\DTO\GoogleEvent\PublishEventRequestDto;
use App\Application\Event\Command\CreateEvent\CreateEventCommand;
use App\Application\Event\Command\PublishEvent\PublishEventCommand;
use App\Application\Event\Query\GetAvailableAttendees\GetAvailableContactsQuery;
use App\Application\Event\Query\GetFreebusy\GetFreeBusyQuery;
use App\Application\Event\Query\GetMyEvents\GetMyEventsQuery;
use App\Application\Exceptions\Event\InvalidPlaceholderCountException;
use App\Application\Query\QueryBusInterface;
use App\Infrastructure\Exception\TokenExchanger\FailedExchangeTokenException;
use App\Infrastructure\Security\User\User;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Throwable;

#[Route('/api/events', name: 'events')]
#[OA\Tag(name: 'EventsController')]
#[Security(name: "Bearer")]
class EventsController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly CommandBusInterface $commandBus,
        private readonly TokenStorageInterface $tokenStorage,
    ) {
    }

    #[Route('/user-events', methods: ['GET'], format: 'json')]
    public function getMyEvents(): JsonResponse
    {
        return $this->json($this->queryBus->execute(new GetMyEventsQuery()));
    }
    #[Route('/available-contacts', methods: ['GET'], format: 'json')]
    public function GetAvailableContacts(): JsonResponse
    {
        return $this->json($this->queryBus->execute(new GetAvailableContactsQuery('primary')));
    }

    #[Route('/event', methods: ['POST'], format: 'json')]
    #[OA\Post(
        path: '/api/events/event',
        summary: 'Create a Google Calendar event',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'title', description: 'Event title', type: 'string'),
                    new OA\Property(property: 'description', description: 'Event description', type: 'string'),
                    new OA\Property(property: 'startDateTime', description: 'Event start date and time', type: 'string', format: 'date-time'),
                    new OA\Property(property: 'endDateTime', description: 'Event end date and time', type: 'string', format: 'date-time'),
                    new OA\Property(property: 'location', description: 'Event location', type: 'string'),
                    new OA\Property(property: 'conference', description: 'Conference', type: 'bool'),
                    new OA\Property(
                        property: 'attendees',
                        description: 'List of attendees',
                        type: 'array',
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'email', type: 'string', description: 'Attendee email', default: 'doroshko.m.v@gmail.com'),
                            ],
                            type: 'object',
                        ),
                    ),
                    new OA\Property(
                        property: 'reminders',
                        description: 'List of reminders',
                        type: 'array',
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'method', type: 'string', description: 'Reminder method (email/popup)', default: 'popup'),
                                new OA\Property(property: 'minutes', type: 'integer', description: 'Minutes before the event', default: 10),
                            ],
                            type: 'object',
                        ),
                    ),
                ],
                type: 'object',
            ),
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Event created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'string', example: true),
                    ],
                    type: 'object',
                ),
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'string', example: false),
                        new OA\Property(property: 'detail', type: 'string'),
                    ],
                    type: 'object',
                ),
            ),
            new OA\Response(
                response: 400,
                description: 'Failed to create event',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'string', example: false),
                        new OA\Property(property: 'message', type: 'string'),
                    ],
                    type: 'object',
                ),
            ),
            new OA\Response(
                response: 401,
                description: 'Failed to exchange token',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'string', example: false),
                        new OA\Property(property: 'message', type: 'string'),
                    ],
                    type: 'object',
                ),
            ),
        ]
    )]
    public function createEvent(#[MapRequestPayload] CreateGoogleEventDto $dto): JsonResponse
    {

        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();
        try {
            $this->commandBus->execute(new CreateEventCommand($dto, $user->getEmail()));
        } catch (FailedExchangeTokenException $e) {
            return $this->json(['success' => false, 'message' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        } catch (Throwable $e) {
            return $this->json(['success' => false, 'message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(['success' => true]);
    }

    #[Route('/user-freebusy', methods: ['GET'], format: 'json')]
    #[OA\Get(
        path: '/api/events/user-freebusy',
        summary: 'Get my freebusy',
        parameters: [
            new OA\Parameter(
                name: 'timeMin',
                description: 'The end of the time range for the query. If unspecified, the range will be infinite.',
                in: 'query',
                required: false,
                example: '2024-12-10T13:18:39.891Z',
            ),
            new OA\Parameter(
                name: 'timeMax',
                description: 'The start of the time range for the query. If unspecified, the range will be infinite.',
                in: 'query',
                required: false,
                example: '2024-12-23T13:18:39.891Z',
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Freebusy retrieved successfully',
            ),
        ],
    )]
    public function getFreeBusy(#[MapQueryString]FreeBusyRequestDto $freeBusyRequestDto): JsonResponse
    {
        $data = $this->queryBus->execute(new GetFreeBusyQuery($freeBusyRequestDto));
        return $this->json($data);
    }

    #[Route('/publish', methods: ['POST'], format: 'json')]
    public function publishEventTemplate(#[MapRequestPayload] PublishEventRequestDto $dto): JsonResponse
    {
        try {
            $this->commandBus->execute(new PublishEventCommand($dto, $this->getUser()->getEmail()));
        } catch (InvalidPlaceholderCountException $e) {
            return $this->json(['success' => false, 'message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        return $this->json(['success' => true]);
    }
}
