<?php

namespace App\Enum;

/**
 * `Sensor` events.
 */
enum EventType: string
{
    case STATUS_CHANGED = 'status_changed';
    case VALUE_CHANGED = 'value_changed';
}
