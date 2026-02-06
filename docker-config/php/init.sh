#!/bin/sh
set -e

echo "--- Menjalankan Inisialisasi sppibuleleng ---"

# 1. Tunggu DB ready (Tetap dipertahankan sebagai failsafe)
while ! nc -z db 3306; do
  echo "Menunggu database di port 3306..."
  sleep 2
done

# 2. Pastikan direktori storage ada
echo "Menyiapkan folder framework storage..."
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/testing

# 3. Generate APP_KEY jika belum ada
if [ -z "$APP_KEY" ]; then
    echo "APP_KEY kosong, men-generate key baru..."
    php artisan key:generate --show
fi

# 4. Install dependencies & Assets
# Optimasi: Gunakan flag --prefer-dist dan --no-dev untuk production
if [ "$APP_ENV" = "production" ]; then
    echo "Mode Production: Install composer tanpa dev-dependencies..."
    composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
    
    if [ -f "package.json" ]; then
        echo "Building production assets..."
        npm run build
    fi
else
    echo "Mode Development: Install composer..."
    composer install --no-interaction
    # Dalam dev, biasanya kita menggunakan 'npm run dev' di luar container
fi

# 5. Database Migration
# --force wajib untuk production agar tidak meminta konfirmasi manual
php artisan migrate --force

# 6. Storage Link
# Gunakan check sederhana sebelum mencoba membuat link agar log tidak kotor
if [ ! -L "public/storage" ]; then
    echo "Membuat storage symlink..."
    php artisan storage:link --force
else
    echo "Symlink storage sudah ada."
fi

# 7. Permission Fixing
# Optimasi: Hanya jalankan chown jika diperlukan untuk menghemat waktu I/O
echo "Mengatur kepemilikan folder storage & cache..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# 8. Cache Management (Optimasi: Hapus redundant clear commands)
if [ "$APP_ENV" = "production" ]; then
    echo "Mengaktifkan cache production..."
    php artisan optimize
else
    echo "Membersihkan cache development..."
    php artisan config:clear
    php artisan cache:clear
fi

echo "--- Setup Selesai, Menjalankan PHP-FPM ---"
exec php-fpm