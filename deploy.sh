#!/bin/bash
set -euo pipefail

# ═══════════════════════════════════════════════════════════════════
# TMJ Operations Dashboard — Skrip Deploy VPS
# ═══════════════════════════════════════════════════════════════════
# Penggunaan: bash deploy.sh
#
# Skrip ini TIDAK PERNAH menghapus data: hanya `migrate --force`
# (migrasi bersifat menambah) dan selalu membuat backup database dulu.
# Jika backup gagal, deploy dihentikan.
#
# Akun dibuat manual: php artisan user:create
# Perbaikan role akun lama: php artisan user:fix-roles --dry-run, lalu
#                           php artisan user:fix-roles
# ═══════════════════════════════════════════════════════════════════

BRANCH="maintenance"
PHP_FPM_SERVICE="php8.3-fpm"
BACKUP_DIR="backups"

echo ""
echo "═══════════════════════════════════════════════"
echo "  🚀 TMJ Deployment — $(date '+%Y-%m-%d %H:%M:%S')"
echo "═══════════════════════════════════════════════"
echo ""

# ─── Backup Database (wajib berhasil) ───────────────────────────
echo "💾 Membuat backup database..."
if [ ! -f .env ]; then
    echo "❌ File .env tidak ditemukan. Deploy dihentikan."
    exit 1
fi

# Baca kredensial DB dari .env (tanpa menampilkan password)
env_val() { grep -E "^$1=" .env | head -n1 | cut -d= -f2- | tr -d '"' | tr -d "'"; }
DB_HOST="$(env_val DB_HOST)";         DB_HOST="${DB_HOST:-127.0.0.1}"
DB_PORT="$(env_val DB_PORT)";         DB_PORT="${DB_PORT:-3306}"
DB_DATABASE="$(env_val DB_DATABASE)"
DB_USERNAME="$(env_val DB_USERNAME)"
DB_PASSWORD="$(env_val DB_PASSWORD)"

if [ -z "$DB_DATABASE" ] || [ -z "$DB_USERNAME" ]; then
    echo "❌ DB_DATABASE / DB_USERNAME kosong di .env. Deploy dihentikan."
    exit 1
fi

mkdir -p "$BACKUP_DIR"
BACKUP_FILE="${BACKUP_DIR}/${DB_DATABASE}_$(date '+%Y%m%d_%H%M%S').sql.gz"

# Password dikirim lewat env MYSQL_PWD agar tidak tampil di daftar proses
if ! MYSQL_PWD="$DB_PASSWORD" mysqldump \
        --host="$DB_HOST" --port="$DB_PORT" --user="$DB_USERNAME" \
        --single-transaction --quick --routines --triggers \
        "$DB_DATABASE" | gzip > "$BACKUP_FILE"; then
    echo "❌ Backup database GAGAL. Deploy dihentikan, tidak ada yang diubah."
    rm -f "$BACKUP_FILE"
    exit 1
fi

if [ ! -s "$BACKUP_FILE" ]; then
    echo "❌ File backup kosong. Deploy dihentikan."
    rm -f "$BACKUP_FILE"
    exit 1
fi
echo "✅ Backup tersimpan: $BACKUP_FILE ($(du -h "$BACKUP_FILE" | cut -f1))"

# ─── Pull Latest Code ───────────────────────────────────────────
echo "📦 Mengambil kode terbaru dari origin/${BRANCH}..."
git fetch origin "${BRANCH}"
git reset --hard "origin/${BRANCH}"

# ─── Install PHP Dependencies ───────────────────────────────────
echo "📚 Memasang dependensi PHP..."
composer install --no-dev --optimize-autoloader --no-interaction

# ─── Storage Symlink (idempoten) ────────────────────────────────
if [ ! -L public/storage ]; then
    echo "🔗 Membuat symlink storage..."
    php artisan storage:link
fi

# ─── Database Migration (hanya menambah, tidak pernah fresh) ───
echo "🗃️  Menjalankan migrasi database..."
php artisan migrate --force

# ─── Clear & Optimize ──────────────────────────────────────────
echo "🧹 Membersihkan cache..."
php artisan optimize:clear

echo "⚡ Optimasi untuk production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# ─── Set Permissions ───────────────────────────────────────────
# Pemilik = user deploy (agar git pull berikutnya tidak gagal "Permission
# denied" di storage/), grup = www-data dengan izin tulis untuk PHP-FPM.
echo "🔒 Mengatur izin file..."
sudo chown -R "$(whoami)":www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# ─── Restart PHP-FPM ──────────────────────────────────────────
echo "🔄 Me-restart ${PHP_FPM_SERVICE}..."
sudo systemctl restart "${PHP_FPM_SERVICE}"

echo ""
echo "═══════════════════════════════════════════════"
echo "  ✅ Deploy selesai!"
echo "  💾 Backup: $BACKUP_FILE"
echo "  📅 $(date '+%Y-%m-%d %H:%M:%S')"
echo "═══════════════════════════════════════════════"
echo ""
