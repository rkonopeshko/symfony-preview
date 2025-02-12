<?php

declare(strict_types=1);

namespace App\Application\DTO\GoogleEvent;

use Symfony\Component\Validator\Constraints as Assert;
use DateTimeInterface;

class PublishEventRequestDto
{
    /**
     * @param int $eventTemplateId
     * @param string[] $titlePlaceholders
     * @param string[] $descriptionPlaceholders
     * @param DateTimeInterface $start
     * @param string[] $attendees
     * @param string[] $groupAttendees
     */
    public function __construct(
        public int $eventTemplateId,
        public array $titlePlaceholders,
        public array $descriptionPlaceholders,
        public DateTimeInterface $start,
        #[Assert\All([
            new Assert\Email(),
        ])]
        public array $attendees,
        #[Assert\All([
            new Assert\Email(),
        ])]
        public array $groupAttendees
    ) {
    }
}
