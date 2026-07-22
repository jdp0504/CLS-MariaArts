#!/bin/bash

# Create SQLite database if it doesn't exist
touch database/database.sqlite

# Run migrations (idempotent - skips already-run migrations)
php artisan migrate --force

# Seed only if User table is empty (first run after DB creation)
SEED_CHECK=$(php artisan tinker --execute="echo \Illuminate\Support\Facades\DB::table('User')->count();" 2>/dev/null || echo "0")
if [ "$SEED_CHECK" = "0" ]; then
    echo "Empty database detected, seeding..."
    php artisan db:seed --force
fi

# Storage link (ignore if already exists)
php artisan storage:link --force 2>/dev/null

# Start the server
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
