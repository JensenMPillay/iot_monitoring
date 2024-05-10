<?php

namespace App\Entity;

use App\Enum\SensorType;
use App\Repository\SensorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: SensorRepository::class)]
#[Broadcast]
class Sensor
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?SensorType $type = null;

    #[ORM\Column(length: 255)]
    private ?string $value = null;

    #[ORM\Column(length: 255)]
    private ?string $unit = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $uptime = null;

    #[ORM\Column(nullable: true)]
    private ?int $dataSentCount = null;

    #[ORM\Column]
    private ?bool $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'sensors')]
    private ?Module $module = null;


    /**
     * @var Collection<int, Log>
     */
    #[ORM\OneToMany(targetEntity: Log::class, mappedBy: 'sensor', cascade: ["persist", "remove"])]
    private Collection $logs;

    public function __construct()
    {
        $this->logs = new ArrayCollection();
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

    public function getType(): ?SensorType
    {
        return $this->type;
    }

    public function setType(SensorType $type): static
    {
        $this->type = $type;

        if (!$this->unit) {
            switch ($type) {
                case SensorType::HUMIDITY:
                    $this->unit = "%";
                    break;
                case SensorType::LIGHT:
                    $this->unit = "lux";
                    break;
                case SensorType::NOISE:
                    $this->unit = "dB";
                    break;
                case SensorType::SPEED:
                    $this->unit = "km/h";
                    break;
                case SensorType::TEMPERATURE:
                    $this->unit = "celsius";
                    break;
                default:
                    break;
            }
        }

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

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): static
    {
        $this->unit = $unit;

        return $this;
    }

    public function getUptime(): ?\DateTimeImmutable
    {
        return $this->uptime;
    }

    public function setUptime(?\DateTimeImmutable $uptime): static
    {
        $this->uptime = $uptime;

        return $this;
    }

    public function getDataSentCount(): ?int
    {
        return $this->dataSentCount;
    }

    public function setDataSentCount(?int $dataSentCount): static
    {
        $this->dataSentCount = $dataSentCount;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

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


    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): static
    {
        $this->module = $module;

        return $this;
    }

    /**
     * @return Collection<int, Log>
     */
    public function getLogs(): Collection
    {
        return $this->logs;
    }

    public function addLog(Log $log): static
    {
        if (!$this->logs->contains($log)) {
            $this->logs->add($log);
            $log->setSensor($this);
        }

        return $this;
    }

    public function removeLog(Log $log): static
    {
        if ($this->logs->removeElement($log)) {
            // set the owning side to null (unless already changed)
            if ($log->getSensor() === $this) {
                $log->setSensor(null);
            }
        }

        return $this;
    }

    /**
     * Initialize a `Sensor` with SensorType and Module.
     *
     * @param SensorType $type
     * @param Module $module
     * @return static
     */
    public function initializeSensor(SensorType $type, Module $module): static
    {
        $this
            ->setModule($module)
            ->setType($type)
            ->setName($type->value . "_" . $module->getName())
            ->setStatus(true)
            ->setUptime(new \DateTimeImmutable())
            ->setDataSentCount(0)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setRandomValue();
        return $this;
    }

    /**
     * Sets a random `value` respecting the sensor type constraints.
     *
     * @param SensorType|null $type
     * @return static
     */
    public function setRandomValue(?SensorType $type = null): static
    {
        switch ($type ?? $this->type) {
            case SensorType::TEMPERATURE:
                $this->value = strval(mt_rand(-20, 40));
                break;
            case SensorType::LIGHT:
                $this->value = strval(mt_rand(0, 1000));
                break;
            case SensorType::HUMIDITY:
            case SensorType::SPEED:
            case SensorType::NOISE:
                $this->value = strval(mt_rand(0, 100));
                break;
            default:
                $this->value = strval(0);
        }

        return $this;
    }

    /**
     * Sets a random `value` within a specified range, respecting the sensor type constraints.
     *
     * @param int $rangeValue
     * @return static
     */
    public function setRandomValueInRange(int $rangeValue = 5): static
    {
        $currentValue = intval($this->value);

        switch ($type ?? $this->type) {
            case SensorType::TEMPERATURE:
                $minValue = max([$currentValue - $rangeValue, -20]);
                $maxValue = min([$currentValue + $rangeValue, 40]);
                break;
            case SensorType::LIGHT:
                $minValue = max([$currentValue - $rangeValue, 0]);
                $maxValue = min([$currentValue + $rangeValue, 1000]);
                break;
            case SensorType::HUMIDITY:
            case SensorType::SPEED:
            case SensorType::NOISE:
                $minValue = max([$currentValue - $rangeValue, 0]);
                $maxValue = min([$currentValue + $rangeValue, 100]);
                break;
            default:
                return $this->setRandomValue();
                break;
        }

        $randomValue = strval(mt_rand($minValue, $maxValue));

        $this->value = $randomValue;

        return $this;
    }
}
