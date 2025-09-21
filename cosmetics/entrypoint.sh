#!/bin/bash

echo "Waiting for PostgreSQL to be available..."

# Try to connect to the database by checking migration status
until php artisan migrate:status > /dev/null 2>&1; do
  >&2 echo "PostgreSQL is unavailable - sleeping"
  sleep 10
done
# Clear and cache config (important to run after .env is loaded)
echo "🧹 Clearing and caching config, routes, and views..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "PostgreSQL is up - running migrations..."
php artisan migrate --force

echo "Starting Laravel server..."
php artisan serve --host=0.0.0.0 --port=8000
