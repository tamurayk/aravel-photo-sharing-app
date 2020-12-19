# FIXME: php:7.4-fpm-alpine だと gd がインストール出来なかったので、一旦 php:7.4-fpm を使うようにした
FROM php:7.4-fpm

COPY config/php.ini-development /usr/local/etc/php/php.ini

# UploadedFile::fake()->image() を使用するには gd が必要
# gd のインストール方法は https://hub.docker.com/_/php の example を参照
RUN set -eux \
    && apt-get update \
    && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

RUN set -eux \
    && docker-php-ext-install pdo_mysql \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
