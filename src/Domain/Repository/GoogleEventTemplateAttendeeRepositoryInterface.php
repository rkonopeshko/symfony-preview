<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\GoogleEventTemplateAttendee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;

interface GoogleEventTemplateAttendeeRepositoryInterface extends ServiceEntityRepositoryInterface
{
    public function create(GoogleEventTemplateAttendee $attendee): void;
}
