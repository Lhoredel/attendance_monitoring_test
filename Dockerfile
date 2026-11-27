FROM php:8.3-apache AS php

RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev libsqlite3-dev libzip-dev unzip git \
    && docker-php-ext-install pdo_sqlite mbstring xml gd zip \
    && a2enmod rewrite

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock package.json package-lock.json* ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

FROM node:22 AS assets
WORKDIR /app
COPY package.json package-lock.json* vite.config.js ./
RUN npm install
COPY resources ./resources
COPY public ./public
RUN npm run build

FROM php AS app
WORKDIR /var/www/html

COPY . .
COPY --from=assets /app/public/build ./public/build
RUN mkdir -p database storage bootstrap/cache \
    && touch database/database.sqlite \
    && composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader \
    && php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear \
    && chown -R www-data:www-data storage bootstrap/cache database

RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' \
    /etc/apache2/sites-available/000-default.conf

EXPOSE 80
CMD ["apache2-foreground"]
