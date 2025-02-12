<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Enum\GoogleEventTemplateAttendee\AttendeeTypeEnum;
use App\Domain\Exception\User\NotAllowedToUpdateEventTemplateException;
use App\Domain\ValueObject\GoogleEventTemplate\CreatorIdValueObject;
use App\Domain\ValueObject\GoogleEventTemplate\DescriptionValueObject;
use App\Domain\ValueObject\GoogleEventTemplate\DurationValueObject;
use App\Domain\ValueObject\GoogleEventTemplate\LocationValueObject;
use App\Domain\ValueObject\GoogleEventTemplate\TitleValueObject;
use App\Infrastructure\Repository\GoogleEventTemplateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GoogleEventTemplateRepository::class)]
class GoogleEventTemplate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "google_event_template_title")]
    public ?TitleValueObject $title = null;

    #[ORM\Column(type: "google_event_template_description")]
    public ?DescriptionValueObject $description = null;
    #[ORM\Column(type: "google_event_template_location")]
    private ?LocationValueObject $location;
    #[ORM\Column]
    private ?bool $conference;
    #[ORM\Column(type: "google_event_template_creator_id")]
    public CreatorIdValueObject $creatorId;

    #[ORM\Column(type: "google_event_template_duration")]
    public DurationValueObject $duration;

    /**
     * @var Collection<int, GoogleEventTemplateReminder>
     */
    #[ORM\OneToMany(
        targetEntity: GoogleEventTemplateReminder::class,
        mappedBy: 'googleEventTemplate',
        cascade: ["persist", "remove"],
        orphanRemoval: true
    )]
    private Collection $reminders;

    /**
     * @var Collection<int, GoogleEventTemplateAttendee>
     */
    #[ORM\OneToMany(
        targetEntity: GoogleEventTemplateAttendee::class,
        mappedBy: 'googleEventTemplate',
        cascade: ["persist", "remove"],
        orphanRemoval: true
    )]
    private Collection $attendees;

    /**
     * @param Collection<int, GoogleEventTemplateAttendee> $attendees
     */
    public function setAttendees(Collection $attendees): void
    {
        $this->attendees->clear();
        foreach ($attendees as $attendee) {
            $attendee->setGoogleEventTemplate($this);
        }
        $this->attendees = $attendees;
    }

    public function __construct(
        CreatorIdValueObject $creatorId,
        ?TitleValueObject $title,
        ?DescriptionValueObject $description,
        ?LocationValueObject $location,
        DurationValueObject $duration,
        ?bool $conference,
    ) {
        $this->creatorId = $creatorId;
        $this->title = $title;
        $this->description = $description;
        $this->location = $location;
        $this->duration = $duration;
        $this->conference = $conference;
        $this->reminders = new ArrayCollection();
        $this->attendees = new ArrayCollection();
    }

    public function update(
        TitleValueObject $title,
        DescriptionValueObject $description,
        LocationValueObject $location,
        DurationValueObject $duration,
        Collection $reminders,
        Collection $attendees,
        bool $conference,
    ): void {
        $this->title = $title;
        $this->description = $description;
        $this->location = $location;
        $this->duration = $duration;
        $this->conference = $conference;
        $this->reminders = $reminders;
        $this->setAttendees($attendees);
    }

    public function getCreatorId(): CreatorIdValueObject
    {
        return $this->creatorId;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?TitleValueObject
    {
        return $this->title;
    }


    public function getDescription(): ?DescriptionValueObject
    {
        return $this->description;
    }
    public function getLocation(): ?LocationValueObject
    {
        return $this->location;
    }
    public function isConference(): ?bool
    {
        return $this->conference;
    }
    /**
     * @throws NotAllowedToUpdateEventTemplateException
     */
    public function validateCanBeUpdated(string $userId): void
    {
        if ($this->getCreatorId() != $userId) {
            throw new NotAllowedToUpdateEventTemplateException();
        }
    }

    /**
     * @return Collection<int, GoogleEventTemplateReminder>
     */
    public function getReminders(): Collection
    {
        return $this->reminders;
    }

    public function addReminder(GoogleEventTemplateReminder $reminder): static
    {
        if (!$this->reminders->contains($reminder)) {
            $this->reminders->add($reminder);
            $reminder->setGoogleEventTemplate($this);
        }

        return $this;
    }

    public function removeReminder(GoogleEventTemplateReminder $reminder): static
    {
        if ($this->reminders->removeElement($reminder)) {
            if ($reminder->getGoogleEventTemplate() === $this) {
                $reminder->setGoogleEventTemplate(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, GoogleEventTemplateAttendee>
     */
    public function getAttendees(): Collection
    {
        return $this->attendees->filter(
            fn (GoogleEventTemplateAttendee $attendee) => $attendee->getAttendeeType() === AttendeeTypeEnum::PERSON
        );
    }
    public function getGroupAttendees(): Collection
    {
        return $this->attendees->filter(
            fn (GoogleEventTemplateAttendee $attendee) => $attendee->getAttendeeType() === AttendeeTypeEnum::GROUP
        );
    }

    public function addAttendee(GoogleEventTemplateAttendee $attendee): static
    {
        if (!$this->attendees->contains($attendee)) {
            $this->attendees->add($attendee);
            $attendee->setGoogleEventTemplate($this);
        }

        return $this;
    }

    public function removeAttendee(GoogleEventTemplateAttendee $attendee): static
    {
        $this->attendees->removeElement($attendee);

        return $this;
    }

    public function getDuration(): DurationValueObject
    {
        return $this->duration;
    }
}
