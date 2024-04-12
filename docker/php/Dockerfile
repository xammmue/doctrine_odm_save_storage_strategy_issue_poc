FROM php:8.2-fpm-bookworm

RUN apt-get update
RUN apt-get install openssl
RUN apt-get install libcurl4-openssl-dev
RUN apt-get install pkg-config
RUN apt-get install libssl-dev

RUN pecl uninstall mongodb
RUN pecl install \
        mongodb \
        apcu

RUN docker-php-ext-enable \
        mongodb

RUN pecl config-set php_ini /etc/php.ini