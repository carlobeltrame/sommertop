FROM php:8.4-apache

WORKDIR /var/www

RUN apt-get -y update && apt-get -y upgrade && apt-get -y install libonig-dev libxml2-dev libzip-dev libpng-dev

ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions xdebug @composer
RUN docker-php-ext-install pdo pdo_mysql mbstring xml bcmath zip gd
COPY docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN a2enmod rewrite
COPY apache-vhost.conf /etc/apache2/sites-enabled/000-default.conf

ENTRYPOINT bash .docker/entrypoint.sh
