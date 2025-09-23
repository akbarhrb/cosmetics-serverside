FROM php:8.2-fpm

WORKDIR /var/www

# Install system packages
RUN apt-get update && apt-get install -y \
    zip unzip curl git libxml2-dev libzip-dev libpng-dev libjpeg-dev libonig-dev \
    sqlite3 libsqlite3-dev libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd zip

# Add Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project source with proper ownership
COPY --chown=www-data:www-data . /var/www

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Entrypoint script
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Expose port 8000 (for php artisan serve) or 9000 (FPM)
EXPOSE 8000

# Use entrypoint to run migrations + start server
CMD ["/entrypoint.sh"]
