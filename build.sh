#!/bin/bash
# Build script for Render deployment (native runtime)

# Install PHP 8.3 and required extensions
apt-get update -y
apt-get install -y php8.3-cli php8.3-mbstring php8.3-xml php8.3-curl php8.3-sqlite3 php8.3-zip php8.3-bcmath php8.3-gd php8.3-intl php8.3-tokenizer php8.3-dom unzip

# Install Composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js (for npm build)
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt-get install -y nodejs

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
