FROM php:8.2-fpm

WORKDIR /var/www

# Install system packages
RUN apt-get update && apt-get install -y \
    zip unzip curl git libxml2-dev libzip-dev libpng-dev libjpeg-dev libonig-dev \
    sqlite3 libsqlite3-dev libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd zip

# Add Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy app source and set permissions
COPY . /var/www
COPY --chown=www-data:www-data . /var/www
RUN chmod -R 755 /var/www

# Install PHP dependencies
RUN composer install

# Generate app key (optional: only if .env exists inside Docker build)
RUN if [ -f ".env" ]; then php artisan key:generate; fi

# ✅ Run session table migration (if using database session driver)
RUN php artisan config:clear && php artisan migrate --force


# Entrypoint
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 8000
CMD ["/entrypoint.sh"]
