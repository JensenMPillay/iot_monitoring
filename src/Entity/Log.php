<?php

namespace App\Entity;

use App\Enum\EventType;
use App\Event\SensorEvent;
use App\Repository\LogRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Ulid;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: LogRepository::class)]
#[Broadcast]
class Log
{
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?Ulid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $event = null;

    #[ORM\Column(length: 255)]
    private ?EventType $type = null;

    #[ORM\ManyToOne(inversedBy: 'logs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Sensor $sensor = null;

    #[ORM\Column(length: 255)]
    private ?string $value = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getEvent(): ?string
    {
        return $this->event;
    }

    public function setEvent(string $event): static
    {
        $this->event = $event;

        return $this;
    }

    public function getType(): ?EventType
    {
        return $this->type;
    }

    public function setType(EventType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getSensor(): ?Sensor
    {
        return $this->sensor;
    }

    public function setSensor(?Sensor $sensor): static
    {
        $this->sensor = $sensor;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Sets a `Log` snapshot from a `SensorEvent`.
     *
     * @param Sensor $sensor
     * @param EventType $eventType
     * @return Log
     */
    public function snapShot(Sensor $sensor, EventType $eventType): static
    {
        $this->event = "event_" . $sensor->getName();
        $this->type = $eventType;
        $this->value = $sensor->getValue();
        $this->sensor = $sensor;
        $this->createdAt = new \DateTimeImmutable();

        return $this;
    }
}
