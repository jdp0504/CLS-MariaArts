#!/bin/bash

touch database/database.sqlite

mkdir -p storage/framework/{cache,sessions,views}
mkdir -p storage/logs
mkdir -p bootstrap/cache
chmod -R 775 storage bootstrap/cache

if [ ! -f .env ] || [ ! -s .env ]; then
    cp .env.example .env
fi

php artisan key:generate --force

php artisan migrate --force

php artisan db:seed --force || true

php artisan storage:link --force 2>/dev/null || true

exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
