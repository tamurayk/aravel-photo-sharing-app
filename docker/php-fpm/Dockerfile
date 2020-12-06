FROM php:7.4-fpm-alpine

COPY config/php.ini-development /usr/local/etc/php/php.ini

RUN set -eux \
    && docker-php-ext-install pdo_mysql \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
