#!/bin/bash

# Attendre que la base de données soit prête (optionnel)
# while ! nc -z $DB_HOST $DB_PORT; do
#   echo "En attente de la base de données..."
#   sleep 1
# done

# Copier .env.example si .env n'existe pas
if [ ! -f .env ]; then
    echo "Création du fichier .env..."
    cp .env.example .env
fi

# Générer la clé Laravel si elle n'existe pas
if [ -z "$(grep '^APP_KEY=..*' .env)" ]; then
    echo "Génération de la clé Laravel..."
    php artisan key:generate
fi

# Configurer la base de données via les variables d'environnement
if [ ! -z "$DB_HOST" ]; then
    sed -i "s/DB_HOST=.*/DB_HOST=$DB_HOST/" .env
fi

if [ ! -z "$DB_PORT" ]; then
    sed -i "s/DB_PORT=.*/DB_PORT=$DB_PORT/" .env
fi

if [ ! -z "$DB_DATABASE" ]; then
    sed -i "s/DB_DATABASE=.*/DB_DATABASE=$DB_DATABASE/" .env
fi

if [ ! -z "$DB_USERNAME" ]; then
    sed -i "s/DB_USERNAME=.*/DB_USERNAME=$DB_USERNAME/" .env
fi

if [ ! -z "$DB_PASSWORD" ]; then
    sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" .env
fi

# Optimiser Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Mettre les bonnes permissions
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

echo "✅ L'application est prête ! Démarrage d'Apache..."
exec apache2-foreground
