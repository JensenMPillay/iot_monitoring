#!/bin/sh
set -e

if [ "$(find ./migrations -iname '*.php' -print -quit)" ]; then
    php bin/console doctrine:migrations:migrate --no-interaction --all-or-nothing
fi

if [ "$(find ./src/DataFixtures -iname '*.php' -print -quit)" ] && [ "$(php bin/console dbal:run-sql "SELECT count(*) FROM module" | grep -oP '\d+')" -eq 0 ]; then
    php bin/console doctrine:fixtures:load --append
fi

if [ "$( find ./src/Scheduler -type f -iname '*.php' -print -quit )" ]; then
    exec php bin/console messenger:consume -v scheduler_default
fi
