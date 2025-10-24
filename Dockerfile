# Utiliser une image PHP avec Apache
FROM php:8.2-apache

# Définir le répertoire de travail
WORKDIR /var/www/html

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Activer le mod rewrite d'Apache
RUN a2enmod rewrite

# Créer la configuration Apache AVEC ALIAS pour storage
RUN echo '<VirtualHost *:80>' > /etc/apache2/sites-available/000-default.conf
RUN echo '    ServerAdmin webmaster@localhost' >> /etc/apache2/sites-available/000-default.conf
RUN echo '    DocumentRoot /var/www/html/public' >> /etc/apache2/sites-available/000-default.conf
RUN echo '' >> /etc/apache2/sites-available/000-default.conf
RUN echo '    <Directory /var/www/html/public>' >> /etc/apache2/sites-available/000-default.conf
RUN echo '        AllowOverride All' >> /etc/apache2/sites-available/000-default.conf
RUN echo '        Require all granted' >> /etc/apache2/sites-available/000-default.conf
RUN echo '        Options Indexes FollowSymLinks' >> /etc/apache2/sites-available/000-default.conf
RUN echo '    </Directory>' >> /etc/apache2/sites-available/000-default.conf
RUN echo '' >> /etc/apache2/sites-available/000-default.conf
RUN echo '    # ✅ ALIAS pour les fichiers storage' >> /etc/apache2/sites-available/000-default.conf
RUN echo '    Alias /storage /var/www/html/storage/app/public' >> /etc/apache2/sites-available/000-default.conf
RUN echo '    <Directory /var/www/html/storage/app/public>' >> /etc/apache2/sites-available/000-default.conf
RUN echo '        Require all granted' >> /etc/apache2/sites-available/000-default.conf
RUN echo '        Options Indexes FollowSymLinks' >> /etc/apache2/sites-available/000-default.conf
RUN echo '    </Directory>' >> /etc/apache2/sites-available/000-default.conf
RUN echo '' >> /etc/apache2/sites-available/000-default.conf
RUN echo '    ErrorLog ${APACHE_LOG_DIR}/error.log' >> /etc/apache2/sites-available/000-default.conf
RUN echo '    CustomLog ${APACHE_LOG_DIR}/access.log combined' >> /etc/apache2/sites-available/000-default.conf
RUN echo '</VirtualHost>' >> /etc/apache2/sites-available/000-default.conf

# Copier les fichiers de l'application
COPY . .

# ✅ AJOUTER CETTE LIGNE - Copier les images uploadées
COPY storage/app/public/ /var/www/html/storage/app/public/

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Installer les dépendances Node.js et builder les assets pour la production
RUN npm install --legacy-peer-deps
RUN npm run build

# Configurer les permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# ✅ AJOUTEZ CETTE LIGNE - Créer le lien symbolique storage
RUN php artisan storage:link
# S'assurer que le dossier build a les bonnes permissions
RUN chown -R www-data:www-data /var/www/html/public/build

# Créer le script d'entrée
RUN echo '#!/bin/bash' > /entrypoint.sh
RUN echo 'set -e' >> /entrypoint.sh
RUN echo '' >> /entrypoint.sh
RUN echo 'echo "Démarrage de l application Laravel..."' >> /entrypoint.sh
RUN echo '' >> /entrypoint.sh
RUN echo '# Vérifier si la clé Laravel existe' >> /entrypoint.sh
RUN echo 'if ! grep -q "^APP_KEY=..*" .env; then' >> /entrypoint.sh
RUN echo '    echo "Génération de la clé Laravel..."' >> /entrypoint.sh
RUN echo '    php artisan key:generate' >> /entrypoint.sh
RUN echo 'fi' >> /entrypoint.sh
RUN echo '' >> /entrypoint.sh
RUN echo '# Forcer l environnement de production pour Vite' >> /entrypoint.sh
RUN echo 'sed -i "s/^APP_ENV=.*/APP_ENV=production/" .env' >> /entrypoint.sh
RUN echo 'sed -i "s/^APP_DEBUG=.*/APP_DEBUG=true/" .env' >> /entrypoint.sh
RUN echo '' >> /entrypoint.sh
RUN echo '# Optimiser Laravel' >> /entrypoint.sh
RUN echo 'php artisan config:clear' >> /entrypoint.sh
RUN echo 'php artisan config:cache' >> /entrypoint.sh
RUN echo 'php artisan route:cache' >> /entrypoint.sh
RUN echo 'php artisan view:cache' >> /entrypoint.sh
RUN echo '' >> /entrypoint.sh
RUN echo 'echo "Application Laravel prête!"' >> /entrypoint.sh
RUN echo 'exec apache2-foreground' >> /entrypoint.sh

RUN chmod +x /entrypoint.sh

# Exposer le port 80
EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
