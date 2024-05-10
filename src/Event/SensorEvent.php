<?php

namespace App\Event;

use App\Entity\Sensor;
use App\Enum\EventType;

class SensorEvent
{
    /**
     * Construct `SensorEvent`.
     *
     * @param Sensor $sensor
     */
    public function __construct(private readonly Sensor $sensor)
    {
    }

    public function getSensor(): Sensor
    {
        return $this->sensor;
    }
}
