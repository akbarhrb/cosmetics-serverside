# Use official PHP image with required extensions
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

# Copy composer files first (enables caching of vendor dependencies)
COPY composer.json composer.lock ./

# Install PHP dependencies (skip post-create-project scripts like artisan migrate)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Now copy the rest of the application
COPY . .

# Set file/folder permissions for Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose Laravel dev server port
EXPOSE 8000

# Run Laravel app with Artisan server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
