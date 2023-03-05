FROM php:8.0-apache

WORKDIR /var/www/html

COPY index.php index.php
COPY src/ src/

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.json
COPY composer.lock composer.lock
RUN composer install --no-dev

EXPOSE 80