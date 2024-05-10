#!/bin/sh
set -e

if [ "$( find ./src/Scheduler -type f -iname '*.php' -print -quit )" ]; then
    exec php bin/console messenger:consume -v scheduler_default
fi
