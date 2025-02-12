<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Enum\GoogleEventTemplateAttendee\AttendeeTypeEnum;
use App\Domain\ValueObject\GoogleEventTemplateAttendee\EmailValueObject;
use App\Infrastructure\Repository\GoogleEventTemplateAttendeeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GoogleEventTemplateAttendeeRepository::class)]
class GoogleEventTemplateAttendee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'attendees')]
    private GoogleEventTemplate $googleEventTemplate;

    #[ORM\Column(type: "google_event_template_attendee_email")]
    private EmailValueObject $email;

    #[ORM\Column(length: 255)]
    private AttendeeTypeEnum $attendeeType;

    public function __construct(
        EmailValueObject $email,
        GoogleEventTemplate $googleEventTemplate,
        AttendeeTypeEnum $attendeeType
    ) {
        $this->attendeeType = $attendeeType;
        $this->email = $email;
        $this->googleEventTemplate = $googleEventTemplate;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGoogleEventTemplate(): ?GoogleEventTemplate
    {
        return $this->googleEventTemplate;
    }

    public function setGoogleEventTemplate(GoogleEventTemplate $googleEventTemplate): static
    {
        $this->googleEventTemplate = $googleEventTemplate;

        return $this;
    }

    public function getEmail(): EmailValueObject
    {
        return $this->email;
    }

    public function getAttendeeType(): AttendeeTypeEnum
    {
        return $this->attendeeType;
    }
}
