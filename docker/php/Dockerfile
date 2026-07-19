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

# Laravel本体をコンテナへコピー
COPY src/ /var/www/

# 本番用パッケージをインストール
RUN composer install \
  --no-dev \
  --optimize-autoloader \
  --no-interaction

# Laravelが書き込むフォルダの権限
RUN chown -R www-data:www-data \
  /var/www/storage \
  /var/www/bootstrap/cache

# RailwayのPORTでLaravelを起動
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]