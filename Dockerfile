FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

RUN apk add --no-cache \
    git \
    curl \
    nginx \
    build-base \
    libpng-dev \
    jpeg-dev \
    libwebp-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    postgresql-dev \
    libxml2-dev \
    oniguruma-dev \
    supervisor \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        gd \
        bcmath \
        exif \
        mbstring \
        opcache \
        zip \
        intl \
        pcntl \
        xml \
        pdo_pgsql \
    && docker-php-ext-enable opcache \
    && rm -rf /tmp/* /var/cache/apk/*

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

COPY . .

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 9000

CMD ["php-fpm"]
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]