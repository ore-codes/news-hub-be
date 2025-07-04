FROM composer:2.7 AS vendor

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --ignore-platform-reqs --no-scripts --no-autoloader

FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
  git \
  unzip \
  libpng-dev \
  libonig-dev \
  libxml2-dev \
  zip \
  curl

RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=vendor /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

COPY --from=vendor /app/vendor /var/www/vendor

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"] 