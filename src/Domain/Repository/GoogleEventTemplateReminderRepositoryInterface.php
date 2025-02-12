<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\GoogleEventTemplateReminder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;

interface GoogleEventTemplateReminderRepositoryInterface extends ServiceEntityRepositoryInterface
{
    public function create(GoogleEventTemplateReminder $googleEventTemplate): void;
    public function update(GoogleEventTemplateReminder $googleEventTemplate): void;
    public function delete(int $id): void;

}
