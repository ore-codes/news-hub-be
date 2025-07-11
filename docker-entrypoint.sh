#!/bin/sh
if [ ! -f vendor/autoload.php ]; then
  composer install --ignore-platform-reqs --no-scripts
fi

php artisan migrate --force || true
php artisan app:fetch-news-articles
php artisan l5-swagger:generate
php artisan jwt:secret

exec "$@" 