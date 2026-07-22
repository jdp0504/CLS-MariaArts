#!/bin/bash

touch database/database.sqlite

php artisan migrate --force

php artisan db:seed --force 2>/dev/null

php artisan storage:link --force 2>/dev/null

exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
