<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\GoogleEventTemplate;
use App\Domain\Exception\EventTemplate\EventTemplateNotFoundException;
use App\Domain\Repository\GoogleEventTemplateRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GoogleEventTemplate>
 */
class GoogleEventTemplateRepository extends ServiceEntityRepository implements GoogleEventTemplateRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GoogleEventTemplate::class);
    }

    public function create(GoogleEventTemplate $googleEventTemplate): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($googleEventTemplate);
        $entityManager->flush();
    }

    public function update(GoogleEventTemplate $googleEventTemplate): void
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

    /**
     * @throws EventTemplateNotFoundException
     */
    public function findById(int $id): GoogleEventTemplate
    {
        $model = $this->find($id);
        if (!$model) {
            throw new EventTemplateNotFoundException('Event not found');
        }
        return $model;
    }

    //    /**
    //     * @return GoogleEventTemplate[] Returns an array of GoogleEventTemplate objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('g.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?GoogleEventTemplate
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

