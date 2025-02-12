<?php

declare(strict_types=1);

namespace App\Application\DTO\GoogleEvent;

use Symfony\Component\Validator\Constraints as Assert;

class AttendeeDto
{
    public function __construct(
        #[Assert\Email]
        public string $email,
    ) {
    }
}
