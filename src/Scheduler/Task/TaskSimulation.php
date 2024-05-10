<?php

namespace App\Scheduler\Task;

use App\Enum\EventType;
use App\Event\SensorEvent;
use App\Repository\ModuleRepository;
use App\Repository\SensorRepository;
use App\Service\SerializationContextBuilder;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Scheduler\Attribute\AsPeriodicTask;
use Symfony\Component\Serializer\SerializerInterface;

#[AsPeriodicTask(frequency: "10 seconds", jitter: 1, method: "simulateEvent")]
class TaskSimulation
{
    /**
     * Constructs Task Simulation.
     *
     * @param EventDispatcherInterface $eventDispatcher
     * @param SensorRepository $sensorRepository
     */
    public function __construct(private readonly EventDispatcherInterface $eventDispatcher, private readonly HubInterface $hub, private readonly SerializerInterface $serializer, private readonly SerializationContextBuilder $serializationContextBuilder, private readonly ModuleRepository $moduleRepository, private readonly SensorRepository $sensorRepository)
    {
    }

    /**
     * Simulates events on random `Sensors` and publish results.
     *
     * @return void
     */
    public function simulateEvent(): void
    {

        $randomSensors = $this->getRandomSensors();
        for ($i = 0; $i < count($randomSensors); $i++) {
            $randomEventType = $this->getRandomEvent();
            $this->eventDispatcher->dispatch(new SensorEvent($randomSensors[$i]), $randomEventType->value);
        }

        $data = $this->serializer->serialize($this->moduleRepository->findAll(), 'json', $this->serializationContextBuilder->buildContext());

        $this->hub->publish(
            new Update(
                '/api',
                $data
            )
        );
    }

    /**
     * Retrieves a list of random `Sensors`.
     *
     * @return \App\Entity\Sensor[]
     */
    public function getRandomSensors(): array
    {
        $sensors = $this->sensorRepository->findAll();

        shuffle($sensors);

        $randomCount = mt_rand(0, count($sensors));

        return array_slice($sensors, 0, $randomCount);
    }

    /**
     * Retrieve a random event type for `Sensors`.
     *
     * @return EventType
     */
    public function getRandomEvent(): EventType
    {
        $randomNumber = mt_rand(1, 10);

        if ($randomNumber === 1) return EventType::STATUS_CHANGED;

        return EventType::VALUE_CHANGED;
    }
}
