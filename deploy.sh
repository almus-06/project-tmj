#!/bin/bash
set -e

echo "🚀 Starting deployment..."

# Pull latest changes
git fetch origin persiapan-deplop
git reset --hard origin/persiapan-deplop

# Install/Update PHP dependencies
composer install --no-dev --optimize-autoloader

# Run database migrations
php artisan migrate --force

# Clear and optimize cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart PHP-FPM to clear opcache if needed
# sudo systemctl restart php8.3-fpm

echo "✅ Deployment finished successfully!"
