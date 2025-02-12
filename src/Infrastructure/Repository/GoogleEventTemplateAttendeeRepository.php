<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\GoogleEventTemplateAttendee;
use App\Domain\Repository\GoogleEventTemplateAttendeeRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GoogleEventTemplateAttendee>
 */
class GoogleEventTemplateAttendeeRepository extends ServiceEntityRepository implements GoogleEventTemplateAttendeeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GoogleEventTemplateAttendee::class);
    }

    public function create(GoogleEventTemplateAttendee $attendee): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($attendee);
        $entityManager->flush();
    }
}
