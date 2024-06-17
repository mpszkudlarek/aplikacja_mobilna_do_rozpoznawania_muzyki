#!/bin/sh
set -e

# Run Symfony migrations
php bin/console doctrine:migrations:migrate --no-interaction

exec php-fpm8.2 -F
