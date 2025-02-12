<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\GoogleEventTemplateReminder;
use App\Domain\Repository\GoogleEventTemplateReminderRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GoogleEventTemplateReminderRepository extends ServiceEntityRepository implements GoogleEventTemplateReminderRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GoogleEventTemplateReminder::class);
    }
    public function create(GoogleEventTemplateReminder $googleEventTemplate): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($googleEventTemplate);
        $entityManager->flush();
    }

    public function delete(int $id): void
    {
        $entityManager = $this->getEntityManager();
        $googleEventTemplate = $this->find($id);
        $entityManager->remove($googleEventTemplate);
        $entityManager->flush();
    }

    public function update(GoogleEventTemplateReminder $googleEventTemplate): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($googleEventTemplate);
        $entityManager->flush();
    }

}
