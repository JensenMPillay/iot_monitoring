<?php

namespace App\Enum;

/**
 * `Sensor` types.
 */
enum SensorType: string
{
    case TEMPERATURE = 'temperature';
    case HUMIDITY = 'humidity';
    case LIGHT = 'light';
    case SPEED = 'speed';
    case NOISE = 'noise';
}
