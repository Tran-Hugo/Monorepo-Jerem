#!/bin/sh
set -e

echo "Symfony warmup..."

# Nettoyage du cache
php bin/console cache:clear --env=prod --no-interaction

# Warmup du cache (container + router + AsController)
php bin/console cache:warmup --env=prod --no-interaction

# Force la compilation des routes (important pour API Platform)
php bin/console debug:router --env=prod > /dev/null

echo "✅ Cache ready"

# Lancer PHP-FPM
exec php-fpm -F
