FROM php:8.4-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    netcat-openbsd \
    nodejs \
    npm \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy file package dulu agar npm bisa jalan
COPY package*.json composer*.json ./

# Jalankan install di level Docker build
RUN rm -f package-lock.json && npm install

# Copy entrypoint dari folder config
COPY docker-config/php/init.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/init.sh

ENTRYPOINT ["init.sh"]