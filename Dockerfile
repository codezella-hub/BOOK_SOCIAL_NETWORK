# Étape de build pour les assets
FROM node:20-alpine as vite-build

WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# Étape de build PHP
FROM php:8.3-fpm-alpine as php-build

WORKDIR /app
COPY . .
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader

# Étape finale
FROM php:8.3-fpm-alpine

# Installer les dépendances système
RUN apk add --no-cache \
    nginx \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    postgresql-dev \
    libzip-dev \
    zip \
    unzip

# Installer les extensions PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    gd \
    zip \
    opcache

# Configurer OPcache pour la production
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=256" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=20000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurer Nginx
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini

# Créer l'utilisateur
RUN adduser -D -u 1000 -g 'www' www-user

# Créer les répertoires
RUN mkdir -p /var/www/html /run/nginx
RUN chown -R www-user:www /var/www/html

# Copier l'application buildée
COPY --chown=www-user:www . /var/www/html
COPY --chown=www-user:www --from=php-build /app/vendor /var/www/html/vendor
COPY --chown=www-user:www --from=vite-build /app/public/build /var/www/html/public/build

WORKDIR /var/www/html

# Configurer les permissions
RUN chown -R www-user:www /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/health || exit 1

EXPOSE 80

COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

CMD ["/start.sh"]
