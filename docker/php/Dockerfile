FROM php:8.3-fpm-bookworm

RUN apt-get update
RUN apt-get install openssl libcurl4-openssl-dev pkg-config libssl-dev

RUN pecl uninstall mongodb
RUN pecl install \
        mongodb \
        apcu

RUN docker-php-ext-enable \
        mongodb

RUN pecl config-set php_ini /etc/php.ini