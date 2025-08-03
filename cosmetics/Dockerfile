FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim unzip git curl \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# ✅ Copy only composer files first
COPY composer.json composer.lock ./

# ✅ Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# ✅ Now copy the rest of the app
COPY . .

# Set permissions
RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache

# Expose port 8000
EXPOSE 8000

# Start Laravel dev server
CMD php artisan serve --host=0.0.0.0 --port=8000
