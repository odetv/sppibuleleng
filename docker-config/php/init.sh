#!/bin/sh
set -e

echo "--- [PRODUCTION MODE] Inisialisasi Project ---"

# 1. Bersihkan Cache Lama
php artisan config:clear
rm -f bootstrap/cache/*.php
echo "Cache bootstrap dibersihkan."

# 2. Tunggu DB ready
while ! nc -z db 3306; do
  echo "Menunggu database (db:3306)..."
  sleep 2
done

# 3. Menyiapkan folder framework storage
echo "Menyiapkan folder framework storage..."
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/testing

# 4. Production Install (Composer & NPM)
echo "Menginstall dependencies production..."
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

if [ -f "package.json" ]; then
  echo "Membangun assets statis (NPM Build)..."
  npm run build
fi

# 5. Database & App Key
[ -z "$APP_KEY" ] && php artisan key:generate --show
php artisan migrate --force
php artisan storage:link --force

# 6. Optimasi Production
echo "Mengoptimalkan cache Laravel..."
php artisan optimize
php artisan view:cache
php artisan event:cache

# 7. Permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "--- Setup PRODUCTION Selesai, Menjalankan PHP-FPM ---"
exec php-fpm