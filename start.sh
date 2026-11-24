#!/bin/bash

# Only needed when running on the local SQLite file
if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
    touch database/database.sqlite
fi

mkdir -p storage/framework/{cache,sessions,views}
mkdir -p storage/logs
mkdir -p bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Adds only new tables/columns; existing data is kept.
# If the database can't be reached, stop here so Render marks the deploy as
# failed and keeps the previous version running.
php artisan migrate --force || { echo "Database migration failed - check DB_* settings"; exit 1; }

# Inserts the demo accounts only when the database is empty
php artisan db:seed --force

php artisan storage:link --force 2>/dev/null || true

# --no-reload passes Render's environment variables (DB_*, etc.) to the web
# server. Without it, "artisan serve" drops them and falls back to .env (SQLite).
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000} --no-reload
