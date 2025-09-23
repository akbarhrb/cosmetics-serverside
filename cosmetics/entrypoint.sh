#!/bin/bash

echo "🚀 Starting Laravel App..."

echo "⏳ Waiting for PostgreSQL to be available..."
until pg_isready -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USERNAME" > /dev/null 2>&1; do
  >&2 echo "PostgreSQL is unavailable - sleeping"
  sleep 10
done

echo "🧹 Clearing and caching config, routes, and views..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🛠 Running migrations..."
php artisan migrate --force

echo "✅ Laravel is ready — starting server..."
php artisan serve --host=0.0.0.0 --port=8000
