<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\GoogleEventTemplate;
use App\Domain\Exception\EventTemplate\EventTemplateNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;

interface GoogleEventTemplateRepositoryInterface extends ServiceEntityRepositoryInterface
{
    /**
     * @throws EventTemplateNotFoundException
     */
    public function findById(int $id): GoogleEventTemplate;
    public function create(GoogleEventTemplate $googleEventTemplate): void;

    public function update(GoogleEventTemplate $googleEventTemplate): void;

    public function delete(int $id): void;
}
