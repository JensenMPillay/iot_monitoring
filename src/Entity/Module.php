<?php

namespace App\Entity;

use App\Repository\ModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\UX\Turbo\Attribute\Broadcast;


#[ORM\Entity(repositoryClass: ModuleRepository::class)]
#[Broadcast]
class Module
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, Sensor>
     */
    #[ORM\OneToMany(targetEntity: Sensor::class, mappedBy: 'module', cascade: ["persist", "remove"])]
    private Collection $sensors;

    public function __construct()
    {
        $this->sensors = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Sensor>
     */
    public function getSensors(): Collection
    {
        return $this->sensors;
    }

    public function getSensorsTypes(): array
    {
        return array_map(function (Sensor $sensor) {
            return $sensor->getType();
        }, $this->getSensors()->toArray());
    }

    public function addSensor(Sensor $sensor): static
    {
        if (!$this->sensors->contains($sensor)) {
            $this->sensors->add($sensor);
            $sensor->setModule($this);
        }

        return $this;
    }

    public function removeSensor(Sensor $sensor): static
    {
        if ($this->sensors->removeElement($sensor)) {
            // set the owning side to null (unless already changed)
            if ($sensor->getModule() === $this) {
                $sensor->setModule(null);
            }
        }

        return $this;
    }
}
