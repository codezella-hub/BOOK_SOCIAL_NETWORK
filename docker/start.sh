#!/bin/sh

# Attendre que la base de données soit prête (pour Render)
if [ ! -z "$DATABASE_URL" ]; then
  echo "Waiting for database to be ready..."
  until nc -z $(echo $DATABASE_URL | sed 's/.*@\([^:]*\):\([0-9]*\).*/\1 \2/'); do
    sleep 1
  done
fi

# Exécuter les migrations si nécessaire
if [ "$RUN_MIGRATIONS" = "true" ]; then
  echo "Running migrations..."
  php artisan migrate --force
fi

# Optimiser l'application
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Démarrer PHP-FPM en arrière-plan
php-fpm -D

# Démarrer Nginx en premier plan
nginx -g 'daemon off;'
