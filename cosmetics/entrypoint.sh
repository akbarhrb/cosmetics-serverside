#!/bin/bash
set -e

echo "🚀 Starting Laravel App..."

# --- Step 1: Check for .env file ---
if [ ! -f .env ]; then
  echo "❌ No .env file found. Exiting..."
  exit 1
fi

# --- Step 2: Wait for PostgreSQL to be ready ---
echo "⏳ Waiting for PostgreSQL to be available..."
until pg_isready -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USERNAME" > /dev/null 2>&1; do
  >&2 echo "PostgreSQL is unavailable - sleeping"
  sleep 5
done
echo "✅ PostgreSQL is up!"

# --- Step 3: Clear and rebuild Laravel caches ---
echo "🧹 Clearing and caching config, routes, and views..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

# --- Step 4: Run database migrations ---
echo "📦 Running migrations..."
php artisan migrate --force

# --- Step 5: Start Laravel app ---
echo "🌍 Starting Laravel server on port ${PORT:-8000}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
