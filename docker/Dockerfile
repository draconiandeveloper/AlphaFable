FROM php:8.3.7RC1-fpm-alpine3.19
RUN mkdir -p /var/www/html

RUN apk update && apk add git zip libsodium-dev libffi-dev libpq-dev
RUN docker-php-ext-install pdo pdo_mysql opcache sodium bcmath ffi