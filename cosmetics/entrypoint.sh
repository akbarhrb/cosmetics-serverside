#!/bin/bash
set -e

echo "🚀 Starting Laravel App..."

# Wait for DB (PHP check instead of pg_isready)
while ! php -r "new PDO('pgsql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE') . ';sslmode=require', getenv('DB_USERNAME'), getenv('DB_PASSWORD'));"; do
  echo "Waiting for database connection..."
  sleep 5
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
