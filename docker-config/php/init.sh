#!/bin/sh
set -e

echo "--- [PRODUCTION MODE] Inisialisasi Project ---"

# 1. Tunggu DB ready
while ! nc -z db 3306; do
  echo "Menunggu database (db:3306)..."
  sleep 2
done

# 2. Menyiapkan folder framework storage
echo "Menyiapkan folder framework storage..."
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/testing

# 3. Production Install (Composer & NPM)
echo "Menginstall dependencies production..."
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# 4. Cek dan Install NPM jika ada package.json
if [ -f "package.json" ]; then
    echo "Installing NPM dependencies..."
    npm install
fi

# 5. Bersihkan Cache Lama
echo "Membersihkan cache lama..."
php artisan config:clear
rm -f bootstrap/cache/*.php

if [ -f "package.json" ]; then
  echo "Membangun assets statis (NPM Build)..."
  npm run build
fi

# 6. Database & App Key
[ -z "$APP_KEY" ] && php artisan key:generate --show
php artisan migrate --force
php artisan storage:link --force

# 7. Optimasi Production
echo "Mengoptimalkan cache Laravel..."
php artisan optimize
php artisan view:cache
php artisan event:cache

# 8. Permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "--- Setup PRODUCTION Selesai, Menjalankan PHP-FPM ---"
exec php-fpm