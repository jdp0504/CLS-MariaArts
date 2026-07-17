#!/bin/bash
# Build script for Render deployment

echo "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "Creating SQLite database file..."
touch database/database.sqlite

echo "Running migrations..."
php artisan migrate --force

echo "Seeding database..."
php artisan db:seed --force

echo "Linking storage..."
php artisan storage:link --force

echo "Installing npm dependencies..."
npm install

echo "Building frontend assets..."
npm run build

echo "Clearing cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Build complete!"
