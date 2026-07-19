FROM php:8.0-fpm

# COPY php.ini /usr/local/etc/php/
COPY docker/php/php.ini /usr/local/etc/php/php.ini

RUN apt update \
  && apt install -y default-mysql-client zlib1g-dev libzip-dev unzip \
  && docker-php-ext-install pdo_mysql zip

RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && composer self-update

WORKDIR /var/www