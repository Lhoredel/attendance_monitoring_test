FROM php:8.3-apache AS php

RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev libsqlite3-dev libzip-dev unzip git \
    && docker-php-ext-install pdo_sqlite mbstring xml gd zip \
    && a2enmod rewrite

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html

FROM node:22 AS assets
WORKDIR /app
COPY package.json ./
COPY vite.config.js ./
COPY public ./public
COPY resources ./resources
RUN npm install && npm run build

FROM php:8.3-apache
WORKDIR /var/www/html/public

COPY . .
COPY --from=assets /app/public/build ./public/build


# Fix Laravel storage and cache permissions
RUN mkdir -p storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

RUN chown -R www-data:www-data storage bootstrap/cache

RUN chmod -R 775 storage bootstrap/cache

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf

RUN a2enmod rewrite

RUN printf '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n' > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel


EXPOSE 80

CMD ["sh", "-c", "test -f .env || cp .env.example .env; grep -q '^APP_KEY=$' .env && php artisan key:generate --force; php artisan migrate --force; apache2-foreground"]
