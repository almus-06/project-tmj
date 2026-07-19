#!/bin/bash
set -e

# ═══════════════════════════════════════════════════════════════════
# TMJ Operations Dashboard — VPS Deployment Script
# ═══════════════════════════════════════════════════════════════════
# Penggunaan:
#   PERTAMA KALI  : bash deploy.sh --init
#   UPDATE BIASA  : bash deploy.sh
# ═══════════════════════════════════════════════════════════════════

BRANCH="maintenance"
PHP_FPM_SERVICE="php8.3-fpm"

echo ""
echo "═══════════════════════════════════════════════"
echo "  🚀 TMJ Deployment — $(date '+%Y-%m-%d %H:%M:%S')"
echo "═══════════════════════════════════════════════"
echo ""

# ─── Pull Latest Code ───────────────────────────────────────────
echo "📦 Pulling latest code from origin/${BRANCH}..."
git fetch origin ${BRANCH}
git reset --hard origin/${BRANCH}

# ─── Install PHP Dependencies ───────────────────────────────────
echo "📚 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# ─── First-time Setup ──────────────────────────────────────────
if [ "$1" == "--init" ]; then
    echo ""
    echo "🔧 === FIRST-TIME INITIALIZATION ==="
    echo ""

    # Generate application key
    if ! grep -q "APP_KEY=base64:" .env; then
        echo "🔑 Generating application key..."
        php artisan key:generate --force
    fi

    # Create storage symlink
    echo "🔗 Creating storage symlink..."
    php artisan storage:link

    # Fresh migration + seed (data master karyawan & unit)
    echo "🗃️  Running fresh migration with seed data..."
    php artisan migrate:fresh --seed --force

    echo ""
    echo "✅ Initialization complete!"
    echo ""
fi

# ─── Database Migration (Incremental) ──────────────────────────
echo "🗃️  Running database migrations..."
php artisan migrate --force

# ─── Clear All Caches ──────────────────────────────────────────
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# ─── Optimize for Production ───────────────────────────────────
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# ─── Set Permissions ───────────────────────────────────────────
echo "🔒 Setting file permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# ─── Restart PHP-FPM ──────────────────────────────────────────
echo "🔄 Restarting ${PHP_FPM_SERVICE}..."
sudo systemctl restart ${PHP_FPM_SERVICE}

echo ""
echo "═══════════════════════════════════════════════"
echo "  ✅ Deployment completed successfully!"
echo "  📅 $(date '+%Y-%m-%d %H:%M:%S')"
echo "═══════════════════════════════════════════════"
echo ""
