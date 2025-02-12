<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Enum\GoogleEventTemplateReminder\ReminderMethodTypeEnum;
use App\Domain\Repository\GoogleEventTemplateReminderRepositoryInterface;
use App\Domain\ValueObject\GoogleEventTemplateReminder\MinutesValueObject;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GoogleEventTemplateReminderRepositoryInterface::class)]
class GoogleEventTemplateReminder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(length: 255)]
    private ReminderMethodTypeEnum $method;
    #[ORM\Column(type: "google_event_template_reminder_minutes")]
    private MinutesValueObject $minutes;

    #[ORM\ManyToOne(targetEntity: GoogleEventTemplate::class, inversedBy: 'reminders')]
    private GoogleEventTemplate $googleEventTemplate;

    public function __construct(
        ReminderMethodTypeEnum $method,
        MinutesValueObject $minutes,
        GoogleEventTemplate $googleEventTemplate,
    ) {
        $this->method = $method;
        $this->minutes = $minutes;
        $this->googleEventTemplate = $googleEventTemplate;
    }

    public function update(ReminderMethodTypeEnum $method, MinutesValueObject $minutes): void
    {
        $this->method = $method;
        $this->minutes = $minutes;
    }

    public function getMethod(): ReminderMethodTypeEnum
    {
        return $this->method;
    }

    public function getMinutes(): MinutesValueObject
    {
        return $this->minutes;
    }

    public function getGoogleEventTemplate(): GoogleEventTemplate
    {
        return $this->googleEventTemplate;
    }

    public function setGoogleEventTemplate(?GoogleEventTemplate $googleEventTemplate): void
    {
        $this->googleEventTemplate = $googleEventTemplate;
    }
}
